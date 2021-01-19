@component('mail::message')
# Comment has been posted on your blog posts

Hi {{ $comment->commentable->user->name }}

@component('mail::button', ['url' => route('posts.show', ['post' => $comment->commentable->id])])
Check your blog post
@endcomponent

@component('mail::button', ['url' => route('users.show', ['user' => $comment->user->id])])
Visit {{ $comment->user->name }} profile
@endcomponent

@component('mail::panel')
{{ $comment->content }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
