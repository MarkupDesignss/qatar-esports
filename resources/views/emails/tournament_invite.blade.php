<!DOCTYPE html>
<html>
<head>
    <title>Team Invitation</title>
</head>
<body>

<h2>You are invited to join a team!</h2>

<p><strong>Tournament:</strong> {{ $tournamentTitle }}</p>
<p><strong>Team:</strong> {{ $teamName }}</p>

<p>Click the link below to join the team:</p>

<p>
    <a href="{{ $inviteUrl }}" target="_blank">
         Join Team
    </a>
</p>

<p><strong>Or copy and paste this link in your browser:</strong></p>

<p style="word-break: break-all;">
    <a href="{{ $inviteUrl }}" target="_blank">
        {{ $inviteUrl }}
    </a>
</p>

<p>This invite link can be shared with other players.</p>

<br>
<p>Thanks,<br>{{ config('mail.from.name') }}</p>

</body>
</html>