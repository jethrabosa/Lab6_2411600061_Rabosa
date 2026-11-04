<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $notification): RedirectResponse
    {
        $record = Auth::user()->notifications()->whereKey($notification)->firstOrFail();
        $record->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }
}
