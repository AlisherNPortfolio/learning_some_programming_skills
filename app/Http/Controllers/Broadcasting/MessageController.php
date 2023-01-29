<?php

namespace App\Http\Controllers\Broadcasting;

use App\Events\NewMessageNotification;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::user()->id;
        $data = ['user_id' => $userId];

        return view('broadcasting.index', $data);
    }

    public function send()
    {
        // ...

        $message = new Message();
        $message->from = 1;
        $message->to = 2;
        $message->message = "Assalomu aleykum";
        $message->save();

        event(new NewMessageNotification($message));
        //...

        return back()->intended('broadcast');
    }
}
