<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendSMS implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;

    protected $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $text)
    {
        $this->phone = $phone;
        $this->text  = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::get('https://api.iqsms.ru/messages/v2/send/', [
            'phone'    => $this->phone,
            'text'     => $this->text,
            'login'    => 'z1677869024369',
            'password' => '864366',
            'sender'   => 'PAPA.LAVASH',
        ]);
    }

}
