<?php

namespace App\Listeners;

use App\Jobs\ThrottledMail;
use App\Mail\BlogPostAdded;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminWhenBlogPostCreated
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        User::thatIsAnAdmin()->get()
            ->map(function (User $user) {
                ThrottledMail::dispatch(
                    new BlogPostAdded(),
                    $user
                );
            });
    }
}
