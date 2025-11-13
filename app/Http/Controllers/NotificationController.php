<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        $unreadCount = auth()->user()->unreadNotifications->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->back()->with('success', 'Уведомление отмечено как прочитанное');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Все уведомления отмечены как прочитанные');
    }

    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Уведомление удалено');
    }

    public function settings()
    {
        $user = auth()->user();
        return view('notifications.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'notify_new_lessons' => 'boolean',
            'notify_comments' => 'boolean',
            'notify_progress' => 'boolean',
            'notify_certificates' => 'boolean',
            'notify_deadlines' => 'boolean',
        ]);

        auth()->user()->update([
            'email_notifications' => $request->has('email_notifications'),
            'notify_new_lessons' => $request->has('notify_new_lessons'),
            'notify_comments' => $request->has('notify_comments'),
            'notify_progress' => $request->has('notify_progress'),
            'notify_certificates' => $request->has('notify_certificates'),
            'notify_deadlines' => $request->has('notify_deadlines'),
        ]);

        return redirect()->back()->with('success', 'Настройки уведомлений обновлены');
    }
}
