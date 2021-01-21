@extends('layouts.main')

@section('title')
@parent Главная
@endsection

@section('menu')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><small><b>{{ $news['created_at'] }}</b></small></div>
                <div class="card-body">
                    <h1>{{ $news['title'] }}</h1>
                    <div class="page__main-image" style="background-image:url({{ $news['image'] }}); width: 100%; height: 300px; background-size: contain;" alt="New Image"></div>
                    <p>{!! $news['text'] !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
