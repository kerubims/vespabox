<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all unread notifications for the authenticated customer.
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
            $notification = $request->user()->notifications()->find($request->id);
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            $request->user()->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
