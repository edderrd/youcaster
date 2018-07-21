<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $guarded = [];

    protected $appends = ['resource'];

    /**
     * Returns a resource public
     *
     * @return string
     */
    public function getResourceAttribute()
    {
        return route('audios.show', $this->id);
    }

    /**
     * Generates a feed mapping for a podcast
     *
     * @return array
     */
    public function toFeed()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'publish_at' => $this->updated_at,
            'guid' => route('podcasts.show', $this->id),
            'url' => $this->resource,
            'type' => 'audio/mpeg',
            'duration' => $this->duration,
            'image' => $this->thumbnail,
        ];
    }
}
