<?php

namespace Shankar\LaravelBasicSetting\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ActivePaymentCommand extends Command
{
    protected $signature = 'payment:active';

    protected $description = 'Active Payment';

    public function handle()
    {
        $date = $this->ask('Please enter the date for the payment: ');
        $filePath = base_path('vendor/shankar/laravel-basic-setting/src/licence_details.json');
        $data = [
            'date' => $date,
        ];
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        File::put($filePath, $jsonData);
        $this->info('Payment activated successfully.');
    }
}
