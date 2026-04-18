<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all unread notifications for the authenticated admin.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()->unreadNotifications()->latest()->take(10)->get();
        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification(s) as read.
     */
    public function markAsRead(Request $request)
    {
        if ($request->id) {
            // Mark specific notification as read
            $notification = $request->user()->notifications()->find($request->id);
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            // Mark all as read
            $request->user()->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
