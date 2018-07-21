<?php

namespace App\Http\Controllers;

use App\Podcast;
use App\Jobs\DownloadVideo;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class PodcastsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentPage = $request->query('page', 1);
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        return Podcast::where('user_id', auth()->id())->latest()->paginate($request->query('limit', 15));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => ['required', 'url']
        ]);
        DownloadVideo::dispatch($request->url);
        return ['success' => 'true', 'message' => 'started to download'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $podcast = Podcast::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $input = [];
        $input['title'] = $request->has('title') ? $request->get('title') : $podcast->title;
        $input['description'] = $request->has('description') ? $request->get('description') : $podcast->description;

        $podcast->update($input);

        return $podcast;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $podcast = Podcast::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $podcast->delete();
        return ['message' => "podcast '{$podcast->title}' successfully deleted"];
    }
}
