<?php

namespace RifRocket\FilamentMailbox\Commands;

use Illuminate\Console\Command;
use RifRocket\FilamentMailbox\Services\ImapService;
use RifRocket\FilamentMailbox\Models\Email;
use Carbon\Carbon;

class FetchEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * You can optionally specify a folder using the --folder option.
     *
     * @var string
     */
    protected $signature = 'mailbox:fetch-emails {--folder=INBOX}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch new emails from the IMAP server and store them in the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $folder = $this->option('folder') ?? 'INBOX';
        $this->info("Fetching emails from folder: {$folder}");

        // Instantiate the IMAP service
        $imapService = new ImapService();

        // Attempt to connect to the IMAP server
        if (!$imapService->connect()) {
            $this->error('Failed to connect to the IMAP server.');
            return 1;
        }

        // Fetch emails with criteria: unseen emails (you can extend criteria as needed)
        $emailsData = $imapService->fetchNewEmails($folder,0, ['unseen' => true]);
        // $emailsData = $imapService->fetchEmails($folder);

        if (empty($emailsData)) {
            $this->info("No new emails found in folder '{$folder}'.");
            return 0;
        }
        // Iterate over the fetched emails and store them in the database
        foreach ($emailsData as $emailData) {
            // Check if the email already exists using the unique UID from the IMAP server
            $existing = Email::where('uid', $emailData['uid'])->first();
            if ($existing) {
                $this->info("Email with UID {$emailData['uid']} already exists. Skipping.");
                continue;
            }
            // Create a new Email record
            Email::create([
                'folder_id'  => null, // Optionally map this to an existing Folder record if needed
                'uid'        => $emailData['uid'],
                'message_id' => $emailData['message_id'],
                'subject'    => $emailData['subject'],
                'from'       => $emailData['from'],
                'to'         => $emailData['to'],
                'cc'         => [], // Extend if needed; otherwise, leave as empty array
                'bcc'        => [], // Extend if needed; otherwise, leave as empty array
                'body'       => $emailData['body'],
                'seen'       => $emailData['seen'] ?? false,
                'email_date' => isset($emailData['email_date'])
                    ? Carbon::parse($emailData['email_date'])
                    : now(),
                'flags'      => $emailData['flags'],
            ]);

            $this->info("Saved email with UID {$emailData['uid']}.");
        }

        $this->info("Email fetching process completed successfully.");
        return 0;
    }
}

