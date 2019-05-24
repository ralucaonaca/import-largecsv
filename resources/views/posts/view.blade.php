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
                        <h1>
                            {{ $post->title }}
                        </h1>
                        <div>
                            {{ $post->body }}
                        </div>

                        <br/>

                         <div id="comments">
                            <ul class="list-group">
                                @foreach($comments as $comment)
                                    <li class="list-group-item">
                                        <strong>
                                            {{ $comment->created_at->diffForHumans() }}
                                        </strong>
                                        {{ $comment->body }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <hr/>
                        <form method="post" action="/posts/{{$post->id}}/comments">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <textarea name="body" placeholder="Your comment Here" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Add Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
