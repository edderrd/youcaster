@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12 col-sm-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <youtube-download></youtube-download>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12 col-sm-12">
            <h5 class="text-secondary">Podcasts</h5>
            <podcasts :limit="{{ $limit }}" :page="{{ $page }}"></podcasts>
        </div>
    </div>
</div>
@endsection
