<?php

namespace RifRocket\FilamentMailbox\Enums;

enum FilamentMailboxEnums: string
{
    case MARK_AS_SEEN   = 'markAsSeen';
    case MARK_AS_UNREAD = 'markAsUnread';
    case FORWARD        = 'forward';
    case REPLY          = 'reply';

    public function icon(): string
    {
        return match($this) {
            self::MARK_AS_SEEN   => 'heroicon-o-envelope-open',
            self::MARK_AS_UNREAD => 'heroicon-o-envelope',
            self::FORWARD        => 'heroicon-o-arrow-uturn-left',
            self::REPLY          => 'heroicon-o-arrow-uturn-right',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::MARK_AS_SEEN   => 'success',
            self::MARK_AS_UNREAD => 'warning',
            self::FORWARD        => 'primary',
            self::REPLY          => 'secondary',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::MARK_AS_SEEN   => 'Mark as Seen',
            self::MARK_AS_UNREAD => 'Mark as Unread',
            self::FORWARD        => 'Forward Email',
            self::REPLY          => 'Reply Email',
        };
    }
}
