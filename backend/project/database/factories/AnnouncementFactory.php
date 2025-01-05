<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('-1 week', '+1 month');
        $endAt = fake()->dateTimeBetween($startAt, '+2 months');
        $bannerStartAt = fake()->boolean(70) ? fake()->dateTimeBetween($startAt, $endAt) : null;
        $bannerEndAt = $bannerStartAt ? fake()->dateTimeBetween($bannerStartAt, $endAt) : null;

        return [
            'title' => fake()->realText(30),
            'content' => fake()->realText(200),
            'category' => fake()->randomElement(['notice', 'maintenance', 'update']),
            'is_published' => fake()->boolean(80), // 80%の確率で公開
            'start_at' => $startAt,
            'end_at' => fake()->boolean(80) ? $endAt : null,
            'show_banner' => fake()->boolean(30), // 30%の確率でバナー表示
            'banner_start_at' => $bannerStartAt,
            'banner_end_at' => $bannerEndAt,
            'send_notification' => fake()->boolean(50),
            'published_mail_sent' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * 公開状態のお知らせを作成
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'start_at' => Carbon::now()->subDays(1),
            'end_at' => Carbon::now()->addMonth(),
        ]);
    }

    /**
     * 未公開状態のお知らせを作成
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }

     /**
     * バナー表示ありのお知らせを作成
     */
    public function withBanner(): static
    {
        return $this->state(fn (array $attributes) => [
            'show_banner' => true,
            'banner_start_at' => Carbon::now(),
            'banner_end_at' => Carbon::now()->addWeek(),
        ]);
    }

    /**
     * メール通知ありのお知らせを作成
     */
    public function withNotification(): static
    {
        return $this->state(fn (array $attributes) => [
            'send_notification' => true,
            'published_mail_sent' => false,
        ]);
    }
}
