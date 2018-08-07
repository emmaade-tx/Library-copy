@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Book</div>

                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form>
                            <label>Search</label><input type="text" name="search">
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h5>Sort in: <strong><a href='/books?order=asc'> Asc.</a></strong> Or <strong><a href="/books?order=desc">Desc.</a></strong></h5>
                    </div>
                </div>
                
                <div class="row">
                    @if(count($books) > 0)
                    @foreach($books as $book)
                        <div class="col-md-4">
                            <img style="width: auto; height: 50%;" src="{{'http://books.toscrape.com/' . $book->url }}"> </img>
                            <br>
                            <strong>{{ $book->title }}</strong>
                            <p>{{ number_format($book->price) }}</p>
                            <p>{{substr($book->description, 0, 10) }} {{ strlen($book->description) > 10 ? '.....': ''}}</p>
                            <a class="btn btn-primary" href="/book/{{ $book->id }}">View</a>
                        </div>
                    @endforeach
                    @else
                        {{-- <div class="row"> --}}
                            <div class="col-md-4">
                                <p>Oops, we have no books to display</p>
                            </div>
                        {{-- </div> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
