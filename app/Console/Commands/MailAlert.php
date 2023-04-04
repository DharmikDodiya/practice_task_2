<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\AlertMail;
use Illuminate\Support\Facades\Log;

class MailAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alert Mail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        if($users){
            foreach($users as $user){
                Log::channel('dharmik')->info("Send Mail Every Minute");
                $user->notify(new AlertMail($user));
            }
        }
        return 0;
    }
}
