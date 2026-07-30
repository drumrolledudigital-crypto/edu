<?php

namespace App\Services;

use App\Models\GoogleAccount;
use App\Models\User;
use Google\Client;
use Google\Service\Oauth2;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use RuntimeException;

class GoogleOAuthService
{
    public function makeClient(): Client
    {
        $client = new Client();
        $client->setClientId(SettingsService::getGoogleClientId());
        $client->setClientSecret(SettingsService::getGoogleClientSecret());
        $client->setRedirectUri(SettingsService::getGoogleRedirectUri());
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setIncludeGrantedScopes(true);
        $client->setScopes(SettingsService::getGoogleScopes());

        return $client;
    }

    public function getAuthUrl(): string
    {
        $this->ensureConfigured();

        $client = $this->makeClient();
        $state = Str::random(40);
        Session::put('google_oauth_state', $state);
        $client->setState($state);

        return $client->createAuthUrl();
    }

    public function handleCallback(string $code, string $state, ?User $user = null): GoogleAccount
    {
        $this->ensureConfigured();

        $expectedState = Session::pull('google_oauth_state');
        if (!$expectedState || !hash_equals($expectedState, $state)) {
            throw new RuntimeException('Invalid OAuth state parameter. Please try connecting again.');
        }

        $user = $user ?: Auth::user();
        if (!$user) {
            throw new RuntimeException('No admin user is available for Google account connection.');
        }

        $client = $this->makeClient();
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new RuntimeException($token['error_description'] ?? $token['error']);
        }

        $client->setAccessToken($token);
        $oauth = new Oauth2($client);
        $profile = $oauth->userinfo->get();

        $existing = GoogleAccount::where('user_id', $user->id)->first();
        $refreshToken = $token['refresh_token'] ?? $existing?->refresh_token;

        if (!$refreshToken) {
            throw new RuntimeException('Google did not return a refresh token. Disconnect access in Google Account permissions and connect again.');
        }

        return GoogleAccount::updateOrCreate(
            ['user_id' => $user->id],
            [
                'google_email' => $profile->email ?: 'google-user-' . Str::lower(Str::random(8)) . '@unknown.local',
                'access_token' => is_array($token) ? json_encode($token) : $token,
                'refresh_token' => $refreshToken,
                'token_expires_at' => $this->expiresAt($token),
            ]
        );
    }

    public function connectedAccount(?User $user = null): ?GoogleAccount
    {
        $user = $user ?: Auth::user();

        return $user ? GoogleAccount::where('user_id', $user->id)->first() : null;
    }

    public function authenticatedClient(?User $user = null): Client
    {
        $account = $this->connectedAccount($user);
        if (!$account) {
            throw new RuntimeException('Google account is not connected.');
        }

        $client = $this->makeClient();
        $client->setAccessToken(is_string($account->access_token) ? $account->access_token : json_decode($account->access_token, true));

        if ($client->isAccessTokenExpired()) {
            $this->refreshToken($account, $client);
        }

        return $client;
    }

    public function refreshToken(GoogleAccount $account, ?Client $client = null): GoogleAccount
    {
        $client = $client ?: $this->makeClient();
        $newToken = $client->fetchAccessTokenWithRefreshToken($account->refresh_token);

        if (isset($newToken['error'])) {
            throw new RuntimeException($newToken['error_description'] ?? $newToken['error']);
        }

        $newToken['refresh_token'] = $account->refresh_token;

        $account->update([
            'access_token' => is_array($newToken) ? json_encode($newToken) : $newToken,
            'token_expires_at' => $this->expiresAt($newToken),
        ]);

        $client->setAccessToken($newToken);

        return $account->refresh();
    }

    public function disconnect(?User $user = null): void
    {
        $account = $this->connectedAccount($user);
        if (!$account) {
            return;
        }

        try {
            $client = $this->makeClient();
            $tokenData = is_string($account->access_token) ? json_decode($account->access_token, true) : $account->access_token;
            $client->revokeToken($tokenData);
        } finally {
            $account->delete();
        }
    }

    public function testConnection(?User $user = null): string
    {
        $client = $this->authenticatedClient($user);
        $oauth = new Oauth2($client);

        return $oauth->userinfo->get()->email;
    }

    public function testCalendarAccess(?User $user = null): bool
    {
        try {
            $client = $this->authenticatedClient($user);
            $calendar = new \Google\Service\Calendar($client);
            $calendar->calendarList->listCalendarList();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function expiresAt(array $token): ?Carbon
    {
        if (!isset($token['expires_in'])) {
            return null;
        }

        return now()->addSeconds((int) $token['expires_in']);
    }

    private function ensureConfigured(): void
    {
        if (blank(SettingsService::getGoogleClientId()) || blank(SettingsService::getGoogleClientSecret()) || blank(SettingsService::getGoogleRedirectUri())) {
            throw new RuntimeException('Google OAuth is not configured. Set Client ID, Client Secret, and Redirect URI in Admin Settings > Google Integrations.');
        }
    }
}
