<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['chat_id', 'sender_id', 'message', 'reply_to_id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

     public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }
    public function chat()
{
    return $this->belongsTo(\App\Models\Chat::class, 'chat_id');
}
}
