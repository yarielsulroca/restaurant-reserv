<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test';
    protected $description = 'Test email configuration';

    public function handle()
    {
        try {
            Mail::to('sulroca@gmail.com')->send(new TestEmail());
            $this->info('Email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Error sending email: ' . $e->getMessage());
        }
    }
}
