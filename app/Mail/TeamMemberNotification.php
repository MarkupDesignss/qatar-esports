<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamMemberNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $team;
    public $tournament;
    public $type; // 'added', 'captain_promoted', 'captain_demoted'

    public function __construct($user, $team, $tournament, $type)
    {
        $this->user = $user;
        $this->team = $team;
        $this->tournament = $tournament;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'added' => 'You have been added to a team',
            'captain_promoted' => 'You are now the team captain',
            'captain_demoted' => 'You are no longer the team captain',
            default => 'Team Notification'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-member-notification',
        );
    }
}