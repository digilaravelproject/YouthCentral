<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markRead($id)
    {
        $student = Auth::guard('student')->user();
        $notification = $student->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }

    public function markUnread($id)
    {
        $student = Auth::guard('student')->user();
        $notification = $student->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->update(['read_at' => null]);
        }
        return back();
    }
}
