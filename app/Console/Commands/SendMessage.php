<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;

use App\Models\DayPrice;
use App\Models\Event;
use App\Models\Kid;
use App\Models\UserDetail;
use App\Models\UserProduct;
use App\Models\PaymentMethod;
use Carbon;
use Illuminate\Support\Facades\DB;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sendMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'After Play Time Send Thanks Message';

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
        $currentTime = Carbon\Carbon::now()->format('H:i:s');
        $currentDate = Carbon\Carbon::now()->format('Y-m-d');

        $users = User::with('kid', 'userDetails')->where('role', '!=', 0)->whereDate('created_at',$currentDate)->latest()->get();

        foreach($users as $user)
        {
            $mobile = $user['userDetails']->phone_no;
            if($user['kid']->end_time == "Unlimited")
            {
                $endtime = Carbon\Carbon::now()->format('H:i:s')->addHour();
            }else{
            $endtime = $user['kid']->end_time;
            }

            $to = \Carbon\Carbon::createFromFormat('H:i:s', $currentTime);

            $from = \Carbon\Carbon::createFromFormat('H:i:s',$endtime);
            $diff_in_hours = $to->diffInMinutes($from);

            if($to>$from)
            {
                if($diff_in_hours >= 60 && $diff_in_hours < 119)
                {
                    $msg = "Thank you for visiting HappyOn Party & Play. We hope you had a great time. Please spare 2 mins for your valuable review: https://rb.gy/pvkwg";
                    $template_id = "1707168821233182292";
                    sendsms($mobile, $msg, $template_id);
                }
            }

        }

    }
}
