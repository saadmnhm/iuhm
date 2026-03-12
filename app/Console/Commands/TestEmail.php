<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle()
    {
        $recipient = $this->argument('email');

        try {
            Mail::raw('This is a test email from IUHM to verify SMTP configuration.', function ($message) use ($recipient) {
                $message->to($recipient)
                    ->subject('IUHM Test Email - ' . now()->format('Y-m-d H:i:s'));
            });

            $this->info("✅ Email sent successfully to {$recipient}!");
            $this->info('Check the recipient inbox (and spam folder).');
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email:");
            $this->error($e->getMessage());
            $this->newLine();
            $this->warn('Possible issues:');
            $this->warn('1. SMTP credentials are incorrect');
            $this->warn('2. SMTP server smtp.iuhm.org is not reachable');
            $this->warn('3. Port 465 is blocked by firewall');
            $this->warn('4. SSL/TLS certificate issue');
            return 1;
        }
    }
}
