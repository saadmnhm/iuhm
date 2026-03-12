<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'candidat_id',
        'sender_type',
        'sender_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    public function adminSender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /** Mark all unread admin messages in this conversation as read (called by candidat) */
    public static function markAdminMessagesRead(int $candidatId): void
    {
        static::where('candidat_id', $candidatId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /** Mark all unread candidat messages in this conversation as read (called by admin) */
    public static function markCandidatMessagesRead(int $candidatId): void
    {
        static::where('candidat_id', $candidatId)
            ->where('sender_type', 'candidat')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /** Unread count for candidat (messages from admin not yet read) */
    public static function unreadForCandidat(int $candidatId): int
    {
        return static::where('candidat_id', $candidatId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();
    }

    /** Total unread candidat messages across all conversations (for admin badge) */
    public static function totalUnreadForAdmin(): int
    {
        return static::where('sender_type', 'candidat')
            ->where('is_read', false)
            ->count();
    }
}
