<?php

namespace App\Http\Controllers;

use App\User;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function get(){
        return Auth::user()->unreadNotifications;
    }
    public function setReadNotificacion(){
       User::find(0)->unreadNotifications->markAsRead();
    }
}
