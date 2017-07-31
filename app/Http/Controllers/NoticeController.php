<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        return view('notice.index');
    }

    public function follow()
    {
        $user = \Auth::user();
        $user->gotFollowerUnreadNotifications->markAsRead();
        $notifications = $user->gotFollowerNotifications;
        return view('notice.follow', compact('notifications'));
    }

    public function like()
    {
        $user = \Auth::user();
        $user->gotVoteUnreadNotifications->markAsRead();
        $notifications = $user->gotVoteNotifications;
        return view('notice.like', compact('notifications'));
    }

    public function comment()
    {
        $user = \Auth::user();
        $user->receivedCommentUnreadNotifications->markAsRead();
        $notifications = $user->receivedCommentNotifications;
        return view('notice.comment', compact('notifications'));
    }
}
