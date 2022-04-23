<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\WaBlast;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending queue messages';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $messages = Message::where('status','pending')->limit(10)->get();
        foreach($messages as $message)
        {
            $response = WaBlast::doSend($message->to, $message->content, $message->attachment);
            $message->status = 'finish';
            $message->response = $response;
            $message->save();
        }
    }
}
