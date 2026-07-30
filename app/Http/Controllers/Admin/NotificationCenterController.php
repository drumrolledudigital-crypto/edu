<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationCenterController extends Controller
{
    public function index()
    {
        return view('admin.notification-center.index');
    }

    public function list(Request $request)
    {
        $query = AdminNotification::with('user')->where('user_id', auth()->id())->latest();

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        $notifications = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    public function markRead($id)
    {
        $notification = AdminNotification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['status' => 'read', 'read_at' => now()]);

        return response()->json(['status' => 'success', 'message' => 'Notification marked as read.']);
    }

    public function markUnread($id)
    {
        $notification = AdminNotification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['status' => 'unread', 'read_at' => null]);

        return response()->json(['status' => 'success', 'message' => 'Notification marked as unread.']);
    }

    public function markAllRead()
    {
        AdminNotification::where('status', 'unread')
            ->where('user_id', auth()->id())
            ->update(['status' => 'read', 'read_at' => now()]);

        return response()->json(['status' => 'success', 'message' => 'All notifications marked as read.']);
    }

    public function archive($id)
    {
        $notification = AdminNotification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['status' => 'archived']);

        return response()->json(['status' => 'success', 'message' => 'Notification archived.']);
    }

    public function destroy($id)
    {
        $notification = AdminNotification::where('user_id', auth()->id())->findOrFail($id);
        $notification->delete();

        return response()->json(['status' => 'success', 'message' => 'Notification deleted.']);
    }
}
