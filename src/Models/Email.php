<?php

namespace RifRocket\FilamentMailbox\Models;

use Database\Factories\EmailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * (Optional: Laravel will default to "emails" if not specified)
     *
     * @var string
     */
    protected $table = 'emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'folder_id',
        'uid',
        'user_id',
        'message_id',
        'subject',
        'from',
        'to',
        'cc',
        'bcc',
        'body',
        'seen',
        'email_date',
        'flags',
    ];

    /**
     * The attributes that should be cast.
     *
     * - "to", "cc", "bcc" and "flags" are stored as JSON.
     * - "seen" is cast to a boolean.
     * - "email_date" is automatically cast to a datetime instance.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'to'         => 'array',
        'cc'         => 'array',
        'bcc'        => 'array',
        'seen'       => 'boolean',
        'email_date' => 'datetime',
        'flags'      => 'array',
       
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return EmailFactory::new();
    }

    // Improved method to fetch complete mail chain including the current email sorted by email_date.
    public function timeLine()
    {
        return self::where('message_id', $this->message_id)
                   ->orderBy('email_date', 'asc')
                   ->get();
    }
}

