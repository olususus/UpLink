<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $companyName }} - Service Status Update</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px; text-transform: uppercase; }
        .status-operational { background-color: #d4edda; color: #155724; }
        .status-degraded { background-color: #fff3cd; color: #856404; }
        .status-maintenance { background-color: #d1ecf1; color: #0c5460; }
        .status-outage { background-color: #f8d7da; color: #721c24; }
        .service-info { background-color: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; text-align: center; color: #6c757d; font-size: 14px; }
        .button { display: inline-block; background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $companyName }} Status Update</h1>
            <p>Service status change notification</p>
        </div>

        <div class="service-info">
            <h2>{{ $service->name }}</h2>
            @if($service->description)
                <p><strong>Description:</strong> {{ $service->description }}</p>
            @endif
            <p><strong>URL:</strong> <a href="{{ $service->url }}">{{ $service->url }}</a></p>
        </div>

        <div style="text-align: center; margin: 25px 0;">
            <p><strong>Status Changed From:</strong></p>
            <span class="status-badge status-{{ $previousStatus }}">{{ ucfirst($previousStatus) }}</span>
            <p style="margin: 10px 0; font-size: 18px;">⬇️</p>
            <span class="status-badge status-{{ $currentStatus }}">{{ ucfirst($currentStatus) }}</span>
        </div>

        @if($service->status_message)
            <div style="background-color: #e9ecef; padding: 15px; border-radius: 6px; margin: 20px 0;">
                <strong>Additional Information:</strong><br>
                {{ $service->status_message }}
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $statusPageUrl }}" class="button">View Full Status Page</a>
        </div>

        @if($supportEmail)
            <p style="text-align: center;">
                If you have any questions, please contact our support team at 
                <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
            </p>
        @endif

        <div class="footer">
            <p>This is an automated notification from {{ $companyName }}.</p>
            <p>To manage your notification preferences, please visit your account settings.</p>
        </div>
    </div>
</body>
</html>
