<?php

namespace App\Mail;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Service $service,
        public string $previousStatus,
        public string $currentStatus
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->currentStatus) {
            'outage' => "🚨 {$this->service->name} - Service Outage",
            'degraded' => "⚠️ {$this->service->name} - Performance Issues",
            'maintenance' => "🔧 {$this->service->name} - Scheduled Maintenance",
            'operational' => "✅ {$this->service->name} - Service Restored",
            default => "📊 {$this->service->name} - Status Update"
        };

        return new Envelope(
            subject: $subject,
            replyTo: config('status.support_email') ? [config('status.support_email')] : []
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-status-changed',
            with: [
                'service' => $this->service,
                'previousStatus' => $this->previousStatus,
                'currentStatus' => $this->currentStatus,
                'companyName' => config('status.company_name', 'Our Company'),
                'supportEmail' => config('status.support_email'),
                'statusPageUrl' => url('/status')
            ]
        );
    }
}
