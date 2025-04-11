<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['body', 'user_id', 'commentable_id', 'commentable_type'];
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
