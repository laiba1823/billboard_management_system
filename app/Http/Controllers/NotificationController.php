<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($notification_id)
    {
        $notification = Notification::find($notification_id);

        if (!$notification) {
            return back()->with('error', 'Notification not found');
        }

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read');
    }
}
