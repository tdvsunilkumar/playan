<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $user;
    public $subject;
    public $approvals;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $email, $user, $userID, $subject, $approvals)
    {
        $this->details = $details;
        $this->email = $email;
        $this->user = $user;
        $this->user_id = $userID;
        $this->subject = $subject;
        $this->approvals = $approvals;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('mails.notification')
                    ->with([
                        'nickname' => $this->user,
                        'email' => $this->email,
                        'approve' => url('/'.$this->approvals['request'].'/approve/'.$this->details->id.'?user='.$this->user_id),
                        'disapprove' => url('/'.$this->approvals['request'].'/disapprove/'.$this->details->id.'?user='.$this->user_id),
                        'messages' => $this->approvals['messages'],
                        'details' => $this->details
                    ]);
    }
}
