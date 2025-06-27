<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\MessageReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Start chat
    public function startChat(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $existingChat = Chat::whereHas('participants', function ($query) use ($request) {
            $query->where('user_id', Auth::id());
        })->whereHas('participants', function ($query) use ($request) {
            $query->where('user_id', $request->receiver_id);
        })->first();

        if ($existingChat) {
            return response()->json(['message' => 'Chat already exists', 'chat_id' => $existingChat->id]);
        }

        $chat = Chat::create();

        ChatParticipant::create(['chat_id' => $chat->id, 'user_id' => Auth::id()]);
        ChatParticipant::create(['chat_id' => $chat->id, 'user_id' => $request->receiver_id]);

        return response()->json(['message' => 'Chat started', 'chat_id' => $chat->id]);
    }

    // Send message
  public function sendMessage(Request $request)
{
    $request->validate([
        'chat_id' => 'required|exists:chats,id',
        'message' => 'required|string',
        'reply_to_id' => 'nullable|exists:messages,id', 
    ]);

    $chat = Chat::findOrFail($request->chat_id);

    if (!$chat->participants()->where('user_id', Auth::id())->exists()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $message = Message::create([
        'chat_id' => $chat->id,
        'sender_id' => Auth::id(),
        'message' => $request->message,
        'reply_to_id' => $request->reply_to_id, 
    ]);

    return response()->json(['message' => 'Message sent', 'data' => $message]);
}


    // Get chat messages
    public function getMessages($chat_id)
    {
        $chat = Chat::findOrFail($chat_id);

        if (!$chat->participants()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()->with('sender')->get();

        return response()->json(['messages' => $messages]);
    }

    // List all chats for the user
    public function listChats(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized user. Please login again.'], 401);
        }

        $chats = Chat::with('participants.user')->whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        if ($chats->isEmpty()) {
            return response()->json(['message' => 'No chats found.'], 200);
        }

        return response()->json([
            'chats' => $chats->map(function ($chat) use ($userId) {
                $receiver = $chat->participants->firstWhere('user_id', '!=', $userId);
                return [
                    'id' => $chat->id,
                    'receiver' => $receiver ? [
                        'id' => $receiver->user->id ?? null,
                        'name' => $receiver->user->name ?? 'Unknown',
                    ] : null,
                ];
            }),
        ]);
    }

    // Edit message
    public function editMessage(Request $request, $message_id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::findOrFail($message_id);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->message = $request->message;
        $message->save();

        return response()->json(['message' => 'Message updated', 'data' => $message]);
    }

    // Delete message
    public function deleteMessage($message_id)
    {
        $message = Message::findOrFail($message_id);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted']);
    }

    // React to message
    public function reactToMessage(Request $request, $message_id)
    {
        $request->validate([
            'reaction' => 'required|string|max:255',
        ]);

        $message = Message::find($message_id);

        if (!$message) {
            return response()->json(['status' => false, 'message' => 'Message not found.'], 404);
        }

        $chat = Chat::find($message->chat_id);

        if (!$chat || !$chat->participants()->where('user_id', Auth::id())->exists()) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $reaction = MessageReaction::updateOrCreate(
            ['message_id' => $message_id, 'user_id' => Auth::id()],
            ['reaction' => $request->reaction]
        );

        return response()->json([
            'status' => true,
            'message' => 'Reaction saved successfully.',
            'data' => $reaction
        ]);
    }

    // Reply to message
    public function replyToMessage(Request $request, $message_id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $originalMessage = Message::find($message_id);

        if (!$originalMessage) {
            return response()->json(['message' => 'Original message not found.'], 404);
        }

        $chat = Chat::find($originalMessage->chat_id);

        if (!$chat || !$chat->participants()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $reply = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'reply_to' => $originalMessage->id, // يجب أن يكون عندك عمود reply_to في جدول messages
        ]);

        return response()->json(['message' => 'Reply sent successfully.', 'data' => $reply]);
    }

    // Search users
    public function searchUsers(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string|min:1',
        ]);

        $searchTerm = $request->search_term;

        $users = User::where('name', 'like', '%' . $searchTerm . '%')
                     ->where('id', '!=', Auth::id())
                     ->get(['id', 'name']);

        return response()->json(['users' => $users]);
    }
}
