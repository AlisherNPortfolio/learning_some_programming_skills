<?php

namespace App\Http\Controllers\Broadcasting;

use App\Events\NewMessageNotification;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::user()->id;
        $users = User::query()->get();
        $data = ['user_id' => $userId, 'users' => $users];

        return view('broadcasting.index', $data);
    }

    public function send(Request $request)
    {
        $request->validate([
            'userId' => 'required|integer|min:0',
            'message' => 'required|string|min:1|max:255',
            'whom' => 'required|integer|min:0'
        ]);

        $message = new Message();
        $message->from = $request->userId;
        $message->to = $request->whom;
        $message->message = $request->message;
        $message->save();

        event(new NewMessageNotification($message));

        return ['success' => true];
    }

    public function authPusher(Request $request)
    {
        $user = auth()->user();
        $socket_id = $request['socket_id'];
        $channel_name = $request['channel_name'];
        $key = getenv('PUSHER_APP_KEY');
        $secret = getenv('PUSHER_APP_SECRET');
        $app_id = getenv('PUSHER_APP_ID');

        if ($user) {

            $pusher = new Pusher($key, $secret, $app_id);
            $auth = $pusher->authorizeChannel($channel_name, $socket_id);

            return response($auth, 200);
        } else {
            header('', true, 403);
            echo "Forbidden";
            return;
        }
    }
}
