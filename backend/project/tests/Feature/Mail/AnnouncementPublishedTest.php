<?php

namespace Tests\Feature\Mail;

use App\Mail\AnnouncementPublished;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AnnouncementPublishedTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcement_email_can_be_sent()
    {
        // メール送信をフェイクに置き換え
        Mail::fake();

        // テスト用のお知らせを作成
        // Factory を使用して、公開済み + メール通知ありのお知らせを生成
        $announcement = Announcement::factory()
            ->published()
            ->withNotification()
            ->create([
                'title' => 'テストタイトル',
                'content' => 'テスト本文',
            ]);

        // メールを送信
        Mail::to('test@example.com')->send(new AnnouncementPublished($announcement));

        // メールが1件送信されたことを確認
        Mail::assertSent(AnnouncementPublished::class, 1);

        // メールの内容を検証
        Mail::assertSent(AnnouncementPublished::class, function ($mail) use ($announcement) {
            // 正しい宛先に送信されているか
            $mail->hasTo('test@example.com');

            // 件名が正しいか
            $envelope = $mail->envelope();
            $this->assertEquals('新しいお知らせが公開されました', $envelope->subject);

            // メール本文にお知らせの内容が含まれているか
            $content = $mail->content();
            $viewData = $content->with;
            $this->assertEquals($announcement->id, $viewData['announcement']->id);
            $this->assertEquals($announcement->title, $viewData['announcement']->title);
            $this->assertEquals($announcement->content, $viewData['announcement']->content);

            // Markdownテンプレートが正しく指定されているか
            $this->assertEquals('emails.announcements.published', $content->markdown);

            return true;
        });
    }

    public function test_announcement_email_renders_correctly()
    {
        // テスト用のお知らせを作成
        $announcement = Announcement::factory()
            ->published()
            ->withNotification()
            ->create([
                'title' => 'テストタイトル',
                'content' => 'テスト本文',
            ]);

        // メールをレンダリング
        $mailable = new AnnouncementPublished($announcement);
        $rendered = $mailable->render();

        // レンダリングされた内容に必要な要素が含まれているか確認
        $this->assertStringContainsString('テストタイトル', $rendered);
        $this->assertStringContainsString('テスト本文', $rendered);
        $this->assertStringContainsString('お知らせを見る', $rendered);
        $this->assertStringContainsString(config('app.url').'/announcements/'.$announcement->id, $rendered);
    }
}
