<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'to_user_id',
        'from_user_id',
        'chat_message',
    ];
}
