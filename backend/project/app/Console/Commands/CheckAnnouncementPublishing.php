<?php

namespace App\Console\Commands;

use App\Jobs\SendAnnouncementPublishedMail;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckAnnouncementPublishing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-announcement-publishing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and publish announcements';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $announcements = Announcement::where('is_published', true)
            ->where('start_at', '<=', $now)
            ->where('published_mail_sent', false)  // メール未送信のものを対象
            ->get();

        foreach ($announcements as $announcement) {
            SendAnnouncementPublishedMail::dispatch($announcement);

            // メール送信済みフラグを更新
            $announcement->update(['published_mail_sent' => true]);
        }

        $this->info('Announcement publishing check completed');
    }
}
