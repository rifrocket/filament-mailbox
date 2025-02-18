<?php
/**
 * Advanced Mailbox Plugin - Mailbox Configuration
 *
 * This configuration file provides settings for:
 * - Mailbox folders: Lists the folder names used for classifying emails.
 * - Resource settings: Defines the Filament resource and model for emails,
 *   along with details for UI navigation, table settings, and search fields.
 * - Pruning settings: Enables scheduled cleanup of emails.
 * - Access control: Configures roles that can access the mailbox.
 * - Attachment storage: Configures the disk and option to store email attachments.
 */

use RifRocket\FilamentMailbox\Filament\Resources\EmailResource;
use RifRocket\FilamentMailbox\Models\Email;

return [

    // Mailbox folder definitions
    'folders' => [
        'INBOX' => 'INBOX',
        'SENT' => 'SENT',
        'DRAFT' => 'DRAFT',
        'TRASH' => 'TRASH',
        'SPAM' => 'SPAM',
    ],
    
    // Resource configuration for Filament
    'resource' => [
        'class' => EmailResource::class, // Resource class handling the mailbox UI
        'model' => Email::class,           // Eloquent model representing an email
        'slug' => 'Mailbox',               // Slug for URL identification

        // Navigation settings for the plugin
        'cluster' => false,
        'navigation_group' => false,        // Group the resource in the navigation menu using navigation_group in translation file
        'navigation_icon' => null,         // Icon for the navigation menu (e.g., 'heroicon-o-envelope')
        'navigation_sort' => null,         // Sorting order in the navigation menu
        
        // Default table configuration settings
        'default_sort_column' => 'created_at',
        'default_sort_direction' => 'desc',
        'datetime_format' => 'Y-m-d H:i:s',

        // Default search fields for the email table
        'table_search_fields' => [
            'subject',
            'from',
            'to',
            'cc',
            'bcc',
        ],
    ],

    // Pruning settings for cleaning up old emails
    'prune_enabled' => true,             // Enable automatic pruning
    'prune_crontab' => '0 0 * * *',        // Cron schedule for email pruning

    // Access control configuration
    'can_access' => [
        'role' => [],                    // Specify roles that have access to the mailbox
    ],

    // Attachment storage settings for emails
    'attachments_disk' => 'local',       // Disk to store email attachments
    'store_attachments' => true,         // Flag to store attachments

];

