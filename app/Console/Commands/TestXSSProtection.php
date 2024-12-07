<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestXSSProtection extends Command
{
    protected $signature = 'test:xss';
    protected $description = 'Test XSS protection in comunidad';

    public function handle()
    {
        $response = Http::post(url('/comunidad'), [
            'content' => '<script>alert("XSS")</script>',
        ]);

        $this->info('Response: ' . $response->body());
    }
}
