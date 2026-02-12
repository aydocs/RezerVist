<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'priority',
        'admin_notes',
        'status',
        'reply',
        'replied_at',
        'closed_at',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(SupportReply::class);
    }
}
