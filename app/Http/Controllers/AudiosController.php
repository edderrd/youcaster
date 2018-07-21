<?php

namespace App\Http\Controllers;

use App\Podcast;

class AudiosController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $podcast = Podcast::findOrFail($id);
        return response()->file($podcast->path);
    }
}
