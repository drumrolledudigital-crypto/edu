<?php

namespace App\Jobs;

use App\Models\EmailLog;
use App\Services\SmtpMailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendLoggedEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $emailLogId,
        public string $view,
        public array $data = [],
    ) {
    }

    public function handle(SmtpMailService $mailService): void
    {
        $log = EmailLog::findOrFail($this->emailLogId);
        $mailService->sendLoggedEmail($log, $this->view, $this->data);
    }

    public function failed(?\Throwable $exception): void
    {
        EmailLog::where('id', $this->emailLogId)->update([
            'status' => 'failed',
            'error_message' => $exception?->getMessage() ?: 'Email job failed.',
        ]);
    }
}
