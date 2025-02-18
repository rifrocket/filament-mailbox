<?php

namespace RifRocket\FilamentMailbox\Services;

use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Log;

class ImapService
{
    /**
     * The Webklex IMAP client instance.
     *
     * @var \Webklex\IMAP\Client
     */
    protected $client;

    /**
     * ImapService constructor.
     *
     * Initializes the IMAP client using the default account configuration.
     */
    public function __construct()
    {
        $this->client = Client::account('default');
    }

    /**
     * Connect to the IMAP server.
     *
     * @return bool Returns true on successful connection, false otherwise.
     */
    public function connect(): bool
    {
        try {
            $this->client->connect();
            return true;
        } catch (\Exception $e) {
            Log::error('IMAP Connection Error: ' . $e->getMessage());
            return false;
        }
    }

/**
 * Fetch emails from a specified folder applying optional filters.
 *
 * @param string $folderName The IMAP folder name (e.g., 'INBOX').
 * @param array  $criteria   Optional criteria such as ['unseen' => true, 'since' => '2025-01-01'].
 *
 * @return array An array of emails with their details.
 */
public function fetchNewEmails(string $folderName = 'INBOX', int $lastProcessedUid, array $criteria = []): array
{
    $emails = [];
    
    try {    
        $folders = $this->client->getFolders($hierarchical = true);
        foreach ($folders as $folder) {
            echo $folder->name . PHP_EOL;
        }
        $folder = $this->client->getFolderByName($folderName);    
        if (!$folder) {
            Log::warning("IMAP Folder not found: {$folderName}");
            return [];
        }
        
        // Retrieve an overview of messages starting from the given UID.
        // Note: The overview uses sequence numbers. This works if UIDs and sequence numbers
        // are roughly aligned and messages haven't been deleted/re-indexed.
        $overviewList = $folder->overview($lastProcessedUid . ":*");
        
        // Optionally, you can apply additional criteria to the full messages after fetching.
        foreach ($overviewList as $key => $overview) {
            // Ensure the UID is actually greater than the last processed UID.
            if ($key <= $lastProcessedUid) {
                continue;
            }
            
            // Retrieve the full message by UID.
            $message = $folder->query()->getMessageByUid($key);
            if (!$message) {
                continue;
            }
            
            // If extra criteria were provided (e.g., unseen or since date), you can check them here.
            // For example, you might skip processing if the message date is older than a given date:
            if (isset($criteria['since'])) {
                $criteriaDate = $criteria['since'] instanceof \Carbon\Carbon 
                    ? $criteria['since'] 
                    : \Carbon\Carbon::parse($criteria['since']);
                if ($message->getDate()->lt($criteriaDate)) {
                    continue;
                }
            }
            if (isset($criteria['unseen']) && $criteria['unseen'] === true) {
                if ($message->getFlag('Seen')) {
                    continue;
                }
            }
            
            // Process sender information safely.
            $fromAddresses = $message->getFrom();
            $from = isset($fromAddresses[0]) ? $fromAddresses[0]->mail : null;
            
            // Convert the "to" recipients into an array if needed.
            $toRecipients = $message->getTo();
            if (is_object($toRecipients) && method_exists($toRecipients, 'all')) {
                $toRecipients = $toRecipients->all();
            } elseif (!is_array($toRecipients)) {
                $toRecipients = [$toRecipients];
            }
            
            $emails[] = [
                'uid'         => $message->getUid(),
                'message_id'  => $message->getMessageId(),
                'subject'     => $message->getSubject(),
                'from'        => $from,
                'to'          => array_map(function ($recipient) {
                    return $recipient->mail ?? null;
                }, $toRecipients),
                'body'        => $message->getHTMLBody() ?: $message->getTextBody(),
                'seen'        => $message->getFlag('Seen'),
                'email_date'  => $message->getDate(),
                'flags'       => $message->getFlags(),
            ];
            
            // Optionally, mark the message as processed (if you wish to update the server flag).
            // $message->setFlag('Processed');  // Use a custom flag if you want to avoid starring the email.
        }
    } catch (\Exception $e) {
        Log::error('IMAP Fetch Emails Error: ' . $e->getMessage());
    }
    
    return $emails;
}




    /**
     * Mark the specified email message as seen on the IMAP server.
     *
     * @param mixed $message The email message instance returned by Webklex.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function markAsSeen($message): bool
    {
        try {
            // Alternatively, you can use the Query API to mark all fetched messages as read:
            // $query->markAsRead();
            $message->setFlag('Seen');
            return true;
        } catch (\Exception $e) {
            Log::error('IMAP Mark As Seen Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark the specified email message as unseen on the IMAP server.
     *
     * @param mixed $message The email message instance returned by Webklex.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function markAsUnseen($message): bool
    {
        try {
            $message->clearFlag('Seen');
            return true;
        } catch (\Exception $e) {
            Log::error('IMAP Mark As Unseen Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Move the specified email message to another folder.
     *
     * @param mixed  $message      The email message instance.
     * @param string $targetFolder The target folder name on the IMAP server.
     *
     * @return bool Returns true on success, false on failure.
     */
    public function moveEmail($message, string $targetFolder): bool
    {
        try {
            $message->move($targetFolder);
            return true;
        } catch (\Exception $e) {
            Log::error('IMAP Move Email Error: ' . $e->getMessage());
            return false;
        }
    }
}
