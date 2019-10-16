<?php

namespace App\Jobs;

use App\Http\Controllers\CommonController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendMail;
use App\Mail\SendResponseMail;
use App\Mail\SendAssignMail;
use App\Mail\SendResponseAssign;
use Illuminate\Support\Facades\Mail;

class QueueMailSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailType;
    protected $mailTo;
    protected $mailInfo;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    public $timeout = 180;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailType, $mailTo, $mailInfo)
    {
        $this->mailType = $mailType;
        $this->mailTo = $mailTo;
        $this->mailInfo = $mailInfo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->mailType == CommonController::MAIL_YC_MOI){
            $mail = new SendMail($this->mailInfo);
        }elseif ($this->mailType == CommonController::MAIL_YC_XU_LY){
            $mail = new SendAssignMail($this->mailInfo);
        }elseif ($this->mailType == CommonController::MAIL_THONG_BAO_DA_YC_XU_LY){
            $mail = new SendResponseAssign($this->mailInfo);
        }elseif ($this->mailType == CommonController::MAIL_THONG_BAO_HOAN_THANH){
            $mail = new SendResponseMail($this->mailInfo);
        }else{
            return false;
        }
        Mail::to($this->mailTo)->send($mail);
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     *
    public function retryUntil()
    {
    return now()->addSeconds(10);
    }
     */
}
