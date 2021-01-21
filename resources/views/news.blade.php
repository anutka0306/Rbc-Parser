@extends('layouts.main')

@section('title')
    @parent Главная
@endsection



@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <p>Hello, People</p>

                        <div class="row">
                            @foreach ($news as $item)
                                <div class="col-md-4">
                                    <a href="{{ route('show',$item) }}"><h4>{{ $item->title }}</h4></a>
                                    <a href="#">
                                        <div class="catalog-item__image" style="background-image:url({{ $item->image}}); height: 150px; background-size: contain;" alt="New Image"></div>
                                    </a>
                                    <div class="new-item__description">
                                        {{ $item->description }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


