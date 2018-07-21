<?php

namespace App\Http\Controllers;

use \Response;
use Illuminate\Http\Request;
use Torann\PodcastFeed\Facades\PodcastFeed;
use App\Podcast;
use App\User;
use Illuminate\Support\Facades\Cache;

class FeedsController extends Controller
{
    /**
     * Return a podcast feed xml
     *
     * @return response
     */
    public function show($token)
    {
        $user = User::where('token', $token)->firstOrFail();

        $feed = Cache::rememberForever('podcasts.feed.' . $user->id, function () use ($user) {
            $podcasts = $user->podcasts()->latest()->get();
            foreach($podcasts as $podcast) {
                PodcastFeed::addMedia($podcast->toFeed());
            }
            return PodcastFeed::toString();
        });

        return Response::make($feed)
            ->header('Content-Type', 'text/xml');
    }
}
