<?php

namespace RifRocket\FilamentMailbox\Enums;

enum EmailFolderEnums: int
{
    case INBOX  = 1;
    case SENT   = 2;
    case DRAFTS = 3;
    case TRASH  = 4;

     public function label(): string
     {
          return match($this) {
               self::INBOX  => 'Inbox',
               self::SENT   => 'Sent',
               self::DRAFTS => 'Drafts',
               self::TRASH  => 'Trash',
          };
     }

     public function icon(): string
     {
          return match($this) {
               self::INBOX  => 'heroicon-o-inbox',
               self::SENT   => 'heroicon-o-paper-airplane',
               self::DRAFTS => 'heroicon-o-document-duplicate',
               self::TRASH  => 'heroicon-o-trash',
          };
     }

     public function color(): string
     {
          return match($this) {
               self::INBOX  => 'primary',
               self::SENT   => 'success',
               self::DRAFTS => 'warning',
               self::TRASH  => 'danger',
          };
     }
}
