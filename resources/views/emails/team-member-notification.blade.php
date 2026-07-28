<!DOCTYPE html>
<html>
<head>
    <title>Team Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .team-info { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .button { display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Team Notification</h2>
        </div>
        <div class="content">
            @if($type == 'added')
                <h3>Hello {{ $user->name ?? $user->first_name ?? '' }}!</h3>
                <p>You have been added to the team <strong>{{ $team->team_name }}</strong> for the tournament:</p>
                <div class="team-info">
                    <p><strong>Tournament:</strong> {{ $tournament->title }}</p>
                    <p><strong>Team:</strong> {{ $team->team_name }}</p>
                    @if($team->team_tag)
                        <p><strong>Team Tag:</strong> {{ $team->team_tag }}</p>
                    @endif
                    <p><strong>Your Role:</strong> Member</p>
                </div>
                <p>Please login to your account to view your team details.</p>

            @elseif($type == 'captain_promoted')
                <h3>Congratulations {{ $user->name ?? $user->first_name ?? '' }}!</h3>
                <p>You have been promoted to <strong>Captain</strong> of the team <strong>{{ $team->team_name }}</strong> for the tournament:</p>
                <div class="team-info">
                    <p><strong>Tournament:</strong> {{ $tournament->title }}</p>
                    <p><strong>Team:</strong> {{ $team->team_name }}</p>
                    <p><strong>Your New Role:</strong> Captain</p>
                </div>
                <p>As captain, you can now manage your team members.</p>

            @elseif($type == 'captain_demoted')
                <h3>Hello {{ $user->name ?? $user->first_name ?? '' }}</h3>
                <p>You are no longer the captain of the team <strong>{{ $team->team_name }}</strong> for the tournament:</p>
                <div class="team-info">
                    <p><strong>Tournament:</strong> {{ $tournament->title }}</p>
                    <p><strong>Team:</strong> {{ $team->team_name }}</p>
                    <p><strong>Your New Role:</strong> Member</p>
                </div>
                <p>The captaincy has been transferred to another team member.</p>
            @endif
        </div>
        <div class="footer">
            <p>This is an automated message from the tournament management system.</p>
        </div>
    </div>
</body>
</html>