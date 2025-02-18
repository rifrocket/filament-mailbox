<?php

return [

    'resource' => [
        'navigation_label' => 'Mailbox',
        'navigation_group' => 'Email Group', 
        'breadcrumb' => 'Mailbox', 
    ],
    'form' => [
        'title' => [
            'label' => 'Title',
            'placeholder' => 'Enter a title',
        ],
        'from' => [
            'label' => 'From',
            'placeholder' => 'Enter the sender\'s email address',
        ],
        'to' => [
            'label' => 'To',
            'placeholder' => 'Enter the recipient\'s email address',
        ],
        'bcc' => [
            'label' => 'BCC',
            'placeholder' => 'Enter the BCC email address',
        ],
        'cc' => [
            'label' => 'CC',
            'placeholder' => 'Enter the CC email address',
        ],
        'created_at' => [
            'label' => 'Date',
        ],
        'subject' => [
            'label' => 'Subject',
            'placeholder' => 'Enter the email subject',
        ],
        'body' => [
            'label' => 'Mail Body',
            'placeholder' => 'Enter the email body',
        ],
        'attachments' => [
            'label' => 'Attachments',
            'placeholder' => 'Select files to attach',
        ],
        'send' => [
            'label' => 'Send',
        ],
    ],
    'table' => [
        'title' => 'Title',
        'from' => 'From',
        'to' => 'To',
        'cc' => 'CC',
        'bcc' => 'BCC',
        'subject' => 'Subject',
        'status' => 'Status',
        'date' => 'Date',
        'actions' => 'Actions',
    ],
    'folders' => [
        'inbox' => 'Inbox',
        'sent' => 'Sent',
        'draft' => 'Draft',
        'trash' => 'Trash',
        'spam' => 'Spam',
    ],
    'status' => [
        'read' => 'Read',
        'unread' => 'Unread',
    ],

];
