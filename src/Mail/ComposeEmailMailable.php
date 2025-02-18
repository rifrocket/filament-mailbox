<?php

namespace RifRocket\FilamentMailbox\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComposeEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The email subject.
     *
     * @var string
     */
    public string $subject;

    /**
     * The email body content.
     *
     * @var string
     */
    public string $body;

    /**
     * The sender's email address.
     *
     * @var string
     */
    public string $fromAddress;

    /**
     * The primary recipients.
     *
     * @var string|array
     */
    public $toAddresses;

    /**
     * Optional CC recipients.
     *
     * @var array
     */
    public array $ccAddresses;

    /**
     * Optional BCC recipients.
     *
     * @var array
     */
    public array $bccAddresses;

    /**
     * Optional attachments paths.
     *
     * @var array
     */
    public array $attachments;

    /**
     * Create a new message instance.
     *
     * @param  string       $subject
     * @param  string       $body
     * @param  string       $fromAddress
     * @param  string|array $toAddresses
     * @param  array        $ccAddresses
     * @param  array        $bccAddresses
     * @param  array        $attachments
     * @return void
     */
    public function __construct(
        string $subject,
        string $body,
        string $fromAddress,
        $toAddresses,
        array $ccAddresses = [],
        array $bccAddresses = [],
        array $attachments = []
    ) {
        $this->subject      = $subject;
        $this->body         = $body;
        $this->fromAddress  = $fromAddress;
        $this->toAddresses  = $toAddresses;
        $this->ccAddresses  = $ccAddresses;
        $this->bccAddresses = $bccAddresses;
        $this->attachments  = $attachments;
    }

    /**
     * Build the message.
     *
     * This method configures the email message by setting the subject,
     * sender, recipients, and attaches any files if provided. The view
     * used here can be replaced with your own custom view that formats
     * the email content appropriately.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject($this->subject)
                      ->from($this->fromAddress)
                      // Using a view from your package's resources.
                      ->view('filament-mailbox::filament.pages.compose-email')
                      ->with([
                          'body' => $this->body,
                      ]);

        // Assign recipients.
        $email->to($this->toAddresses);

        if (!empty($this->ccAddresses)) {
            $email->cc($this->ccAddresses);
        }

        if (!empty($this->bccAddresses)) {
            $email->bcc($this->bccAddresses);
        }

        // Attach files if provided.
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $attachment) {
                $email->attach($attachment);
            }
        }

        return $email;
    }
}

