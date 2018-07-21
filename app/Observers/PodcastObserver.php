<?php

namespace App\Observers;

use App\Podcast;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PodcastObserver
{
    /**
     * Handle to the podcast "created" event.
     *
     * @param  \App\Podcast  $podcast
     * @return void
     */
    public function created(Podcast $podcast)
    {
        Cache::forget("podcasts.feed." . auth()->id());
    }

    /**
     * Handle the podcast "updated" event.
     *
     * @param  \App\Podcast  $podcast
     * @return void
     */
    public function updated(Podcast $podcast)
    {
        Cache::forget("podcasts.feed." . auth()->id());
    }

    /**
     * Handle the podcast "deleted" event.
     *
     * @param  \App\Podcast  $podcast
     * @return void
     */
    public function deleted(Podcast $podcast)
    {
        Cache::forget("podcasts.feed." . auth()->id());
        $path = str_replace(storage_path('app'), '', $podcast->path);
        $response = Storage::delete($path);
    }
}
