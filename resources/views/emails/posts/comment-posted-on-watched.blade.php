@component('mail::message')
# Comment has been posted on a post yea are following

Hi {{ $user->name }}

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
