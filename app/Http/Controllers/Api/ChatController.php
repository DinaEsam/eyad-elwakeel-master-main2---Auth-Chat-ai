<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Start a new chat between a doctor and a patient
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
// Search for users by name
public function searchUsers(Request $request)
{
    $request->validate([
        'search_term' => 'required|string|min:1',
    ]);

    $searchTerm = $request->search_term;

    // Query the users table for users whose name matches the search term
    $users = User::where('name', 'like', '%' . $searchTerm . '%')
                 ->where('id', '!=', Auth::id()) // Exclude the authenticated user
                 ->get(['id', 'name']);

    return response()->json(['users' => $users]);
}
    // Send a message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::findOrFail($request->chat_id);

        if (!$chat->participants()->where('user_id', Auth::id())->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
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

    //////// List user chats///////

    // public function listChats(Request $request)
    // {
    //     $userId = Auth::id(); // Get the authenticated user's ID

    //     // Fetch the chats for the user along with participants
    //     $chats = Chat::with('participants.user')->whereHas('participants', function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     })->get();

    //     return response()->json([
    //         'chats' => $chats->map(function ($chat) use ($userId) {
    //             // Find the receiver's user data
    //             $receiver = $chat->participants->firstWhere('user_id', '!=', $userId); // Get the other participant
    //             return [
    //                 'id' => $chat->id,
    //                 'receiver' => [
    //                     'id' => $receiver->user->id,
    //                     'name' => $receiver->user->name,
    //                 ],
    //             ];
    //         }),
    //     ]);
    // }
    public function listChats(Request $request)
    {
        try {
            $userId = Auth::id(); // الحصول على ID المستخدم الحالي
    
            // التحقق من أن المستخدم مسجل الدخول
            if (!$userId) {
                return response()->json(['error' => 'Unauthorized user. Please login again.'], 401);
            }
    
            // جلب المحادثات التي يشارك فيها المستخدم مع تحميل بيانات المشاركين
            $chats = Chat::with('participants.user')->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    
            // التحقق إذا لم يكن هناك محادثات
            if ($chats->isEmpty()) {
                return response()->json(['message' => 'No chats found.'], 200);
            }
    
            // تنسيق البيانات وإرجاعها
            return response()->json([
                'chats' => $chats->map(function ($chat) use ($userId) {
                    // جلب المرسل والمستقبل
                    $receiver = $chat->participants->firstWhere('user_id', '!=', $userId);
                    
                    // التحقق من أن البيانات موجودة قبل الوصول إليها لتجنب الأخطاء
                    return [
                        'id' => $chat->id,
                        'receiver' => $receiver ? [
                            'id' => $receiver->user->id ?? null,
                            'name' => $receiver->user->name ?? 'Unknown',
                        ] : null,
                    ];
                }),
            ]);
    
        } catch (\Exception $e) {
            // التعامل مع الأخطاء غير المتوقعة وإرجاع رسالة مفصلة
            return response()->json([
                'error' => 'Something went wrong.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    // Edit a message
public function editMessage(Request $request, $message_id)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $message = Message::findOrFail($message_id);

    // Check if the authenticated user is the sender of the message
    if ($message->sender_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Update the message content
    $message->message = $request->message;
    $message->save();

    return response()->json(['message' => 'Message updated', 'data' => $message]);
}

// Delete a message
public function deleteMessage($message_id)
{
    $message = Message::findOrFail($message_id);

    // Check if the authenticated user is the sender of the message
    if ($message->sender_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Delete the message
    $message->delete();

    return response()->json(['message' => 'Message deleted']);
}
}
