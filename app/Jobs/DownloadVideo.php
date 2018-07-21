<?php

namespace App\Jobs;

use \Log;
use \Auth;
use App\Podcast;
use YoutubeDl\YoutubeDl;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Events\YoutubeDownloadProgress;
use Illuminate\Support\Facades\Storage;
use App\Events\YoutubeDownloadCompleted;
use Illuminate\Queue\InteractsWithQueue;
use YoutubeDl\Exception\NotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\PrivateVideoException;
use App\Events\YoutubeDownloadFailed;

class DownloadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    protected $downloader;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->downloader = new YoutubeDl([
            'extract-audio' => true,
            'audio-format' => 'mp3',
            'audio-quality' => 0, // best
            'output' => '%(title)s.%(ext)s',
            'ffmpeg-location' => config('services.youtube-dl.ffmpeg'),
        ]);
        $this->userId = Auth::user() ? Auth::user()->id : 'guest';
        Storage::makeDirectory("videos/$this->userId");
        $this->downloader->setDownloadPath(storage_path("app/videos/$this->userId"));
        $this->downloader->setBinPath(config('services.youtube-dl.bin'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $podcast = Podcast::where('url', $this->url)->where('user_id', $this->userId)->first();
        if ($podcast) {
            return event(new YoutubeDownloadCompleted($podcast, $this->userId));
        }
        $this->downloader->onProgress(function ($progress) {
            event(new YoutubeDownloadProgress($this->url, $this->userId, $progress));
        });

        try {
            $video = $this->downloader->download($this->url);
            $thumbnails = $video->getThumbnails();
            $thumbnail = count($thumbnails) > 0 ? $thumbnails[0] : null;
            $podcast = Podcast::create([
                'title' => $video->getTitle(),
                'author' => $video->getUploader(),
                'description' => $video->getDescription(),
                'path' => storage_path("app/videos/$this->userId") . '/' . $video->getFilename(),
                'url' => $this->url,
                'user_id' => $this->userId,
                'metadata' => json_encode($thumbnails),
                'duration' => $video->getDuration(),
                'format' => $video->getFormat(),
                'thumbnail' => !is_null($thumbnail) ? $thumbnail->getUrl() : null,
            ]);
            $podcast->save();
            event(new YoutubeDownloadCompleted($podcast, $this->userId));
        } catch (NotFoundException $e) {
            Log::error($e->getMessage());
        } catch (PrivateVideoException $e) {
            Log::error($e->getMessage());
        } catch (CopyrightException $e) {
            Log::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Storage::delete("videos/$this->userId/" . $video->getFilename());
            event(new YoutubeDownloadFailed($this->userId));
        }
    }
}
