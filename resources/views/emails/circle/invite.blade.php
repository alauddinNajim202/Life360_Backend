<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>You're Invited!</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #4f46e5; padding: 40px 30px;">
                            <div style="font-size: 48px; margin-bottom: 16px;">👨‍👩‍👧‍👦</div>
                            <h1 style="color: #ffffff; font-size: 28px; margin: 0; font-weight: 700; letter-spacing: 0.5px;">You're Invited!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; color: #374151; font-size: 16px; line-height: 1.6;">
                            <p style="margin: 0 0 20px;">Hello!</p>
                            <p style="margin: 0 0 20px;"><strong>{{ $ownerName }}</strong> has invited you to join their circle: <strong style="color: #111827;">{{ $circleName }}</strong> on <strong>{{ config('app.name', 'Family Tracker') }}</strong>.</p>
                            <p style="margin: 0 0 20px;">Stay connected, share locations, and keep your family safe. To join the circle, simply enter the unique invite code below in your app:</p>
                            
                            <!-- Invite Code Box -->
                            <div style="background-color: #eef2ff; border: 2px dashed #818cf8; border-radius: 12px; padding: 30px; text-align: center; margin: 32px 0;">
                                <span style="display: block; font-size: 14px; color: #4f46e5; font-weight: 700; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1px;">Your Invite Code</span>
                                <p style="font-size: 42px; font-weight: 800; color: #312e81; letter-spacing: 6px; margin: 0; line-height: 1;">{{ $inviteCode }}</p>
                            </div>
                            
                            <p style="font-size: 15px; color: #dc2626; text-align: center; margin-top: -10px; margin-bottom: 30px; font-weight: 500;">
                                ⏳ This invite code will expire in 24 hours.
                            </p>
                            
                            <p style="margin: 0 0 20px;">If you don't have the app yet, download it now to get started!</p>
                            <p style="margin: 0;">Warm regards,<br><strong>The {{ config('app.name', 'Family Tracker') }} Team</strong></p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f9fafb; padding: 24px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px; line-height: 1.5;">
                            <p style="margin: 0 0 8px;">&copy; {{ date('Y') }} {{ config('app.name', 'Family Tracker') }}. All rights reserved.</p>
                            <p style="margin: 0;">If you didn't expect this invitation, you can safely ignore this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
