<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category',
        'is_published',
        'start_at',
        'end_at',
        'show_banner',
        'banner_start_at',
        'banner_end_at',
        'send_notification'
    ];
}
