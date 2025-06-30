<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';

    protected $fillable = [

        'user_id',
        'f_name',
        'l_name',
        'email',
        'massage',
    ];

    public function user()
        {
            return $this->belongsTo(User::class);
        }
}
