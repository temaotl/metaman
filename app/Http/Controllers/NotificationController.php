<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        switch(strtolower(request('show')))
        {
            case 'unread':
                $show = 'unreadNotifications';
                $query = 'unread';
                break;

            case 'read':
                $show = 'readNotifications';
                $query = 'read';
                break;

            default:
                $show = 'notifications';
                $query = 'all';
                break;
        }

        return view('notifications', [
            'show' => $query,
            'notifications' => auth()->user()->$show()->paginate(),
        ]);
    }

    public function update($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        if(is_null($notification->read_at))
        {
            $notification->markAsRead();
        }
        else
        {
            $notification->markAsUnread();
        }

        return redirect('notifications?page=' . request('page'));
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        if($notification) $notification->delete();

        return redirect('notifications?page=' . request('page'));
    }
}
