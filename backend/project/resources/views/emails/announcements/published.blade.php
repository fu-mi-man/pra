<x-mail::message>
# 新しいお知らせ

{{ $announcement->title }}

{{ $announcement->content }}

<x-mail::button :url="config('app.url').'/announcements/'.$announcement->id">
お知らせを見る
</x-mail::button>

</x-mail::message>
