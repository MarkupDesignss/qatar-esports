<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TournamentInviteMail extends Mailable
{
    use SerializesModels;

    public $inviteUrl;
    public $tournamentTitle;
    public $teamName;

    public function __construct($inviteUrl, $tournamentTitle, $teamName)
    {
        $this->inviteUrl = $inviteUrl;
        $this->tournamentTitle = $tournamentTitle;
        $this->teamName = $teamName;
    }

    public function build()
    {
        return $this->from(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            )
            ->subject('Team Invitation – Qatar Esports')
            ->view('emails.tournament_invite');
    }
}