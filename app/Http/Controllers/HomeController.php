<?php

namespace App\Http\Controllers;

use App\ChatMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::where('id','!=',Auth::user()->id)->paginate(10);
        return view('home',compact('users'));
    }

    public function renderConversationList(Request $request)
    {
        $conversationList = ChatMessage::where('to_user_id',$request->get('getUserSendToId'))
                                        ->orWhere('from_user_id',$request->get('getUserSendToId'))
                                        ->get();
        $view = view("conversationList",compact('conversationList'))->render();
        return response()->json(['html'=>$view]);
    }
    public function sendMessage(Request $request)
    {
        try{
            $getSentToID = $request->get('getUserSendToId');
            $message = $request->get('message');
            ChatMessage::create([
                'to_user_id'    => $getSentToID,
                'from_user_id'  => Auth::user()->id,
                'chat_message'  => $message,
            ]);
            return response()->json([
                'status'    => 'success',
                'message'   => 'Message Sent Successfully.'
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }
}
