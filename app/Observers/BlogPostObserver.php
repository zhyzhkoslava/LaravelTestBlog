<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;

class BlogPostObserver
{
    /**
     * processing BEFORE CREATING post
     *
     * @param \App\Models\BlogPost $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);

        $this->setSlug($blogPost);

        $this->setHtml($blogPost);

        $this->setUser($blogPost);
//        dd($blogPost);
    }

    /**
     * processing BEFORE UPDATING post
     *
     * @param \App\Models\BlogPost $blogPost
     * @return void
     */
    public function updating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);

        $this->setSlug($blogPost);
    }

    /**
     * Set value content_html relatively content_raw
     * content_html - no modifiable, autofill
     * content_raw - modifiable
     *
     * @param BlogPost $blogPost
     */
    protected function setHtml($blogPost)
    {
        if ($blogPost->isDirty('content_raw')) {
            //TODO:markdown->html
            $blogPost->content_raw = $blogPost->content_raw;
        }
    }

    /** If user_id not defined than use default user
     *
     * @param BlogPost $blogPost
     */
    protected function setUser($blogPost)
    {
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }

    /**
     *
     * @param BlogPost $blogPost
     */
    protected function setPublishedAt(BlogPost $blogPost)
    {
//        dd(__METHOD__,empty($blogPost->published_at) && $blogPost->is_published);
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * If $blogPost->slug not empty we fill it with $blogPost->title
     *
     * @param BlogPost $blogPost
     */
    protected function setSlug(BlogPost $blogPost)
    {
//        dd(__METHOD__, empty($blogPost->slug));
        if (empty($blogPost->slug)) {
            $blogPost->slug = \Str::slug($blogPost->title);
        }
    }

    /**
     * Handle the models blog post "deleted" event.
     *
     * @param \App\Models\BlogPost $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the models blog post "restored" event.
     *
     * @param \App\Models\BlogPost $blogPost
     * @return void
     */
    public function restored(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the models blog post "force deleted" event.
     *
     * @param \App\Models\BlogPost $blogPost
     * @return void
     */
    public function forceDeleted(BlogPost $blogPost)
    {
        //
    }
}
