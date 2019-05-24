@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach($topics as $topic)

                        <div class="blog-post">
                            <h2 class="blog-post-title">{{ $topic->title }}</h2>
                            <blockquote>
                                <p>{{ $topic->body }}</p>
                                <a href="posts/{{$topic->id}}" class="btn btn-primary btn-sm">Learn more</a>
                            </blockquote>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
