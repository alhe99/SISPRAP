<?php

namespace App\Jobs;

use App\Notifications\NotifyPreRegisterProject;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class sendNotificationToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $GlobalData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
         $this->GlobalData = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         User::FindOrFail(0)->notify(new NotifyPreRegisterProject($this->GlobalData));
    }
}
