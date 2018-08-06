@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Book</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="row">
                        <div class="col-md-12">
                            <img src="{{ $book->url }}"> </img>
                            <br>
                            <strong>{{ $book->title }}</strong>
                            <p>{{ number_format($book->price) }}</p>
                            <p>{{ $book->description }}</p>
                            <a class="btn btn-primary" href="/edit/book/{{ $book->id }}">Edit</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
