<?php

namespace App\Jobs;

use App\Mail\AnnouncementPublished;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAnnouncementPublishedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $announcement;

    /**
     * Create a new job instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->announcement->send_notification) {
            return;
        }
        // すべてのユーザーにメールを送信
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)
                    ->queue(new AnnouncementPublished($this->announcement));
            }
        });
    }
}
