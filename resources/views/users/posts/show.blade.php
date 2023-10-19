@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
<style>
    .col-4{
        overflow-y: scroll;
    }
    .card-body{
        position:absolute;
        top:65px;    /* giving a space at card body and card header */
       
    }
</style>
<div class="row border shadow">
    <div class="col p-0 border-end">
        <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
    </div>
    <div class="col-4 px-0 bg-white">
        <div class="card boder-0">
            <div class="card-header bg-white">
                <div class="row align-item-center">
                    <div class="col-auto">
                        <a href="{{route('profile.show', $post->user->id)}}">
                            @if ($post->user->avatar)
                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle avatar-sm">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                            @endif
                        </a>
                    </div>
                    
                    <div class="col ps-0">
                        <a href="{{route('profile.show', $post->user->id)}}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                    </div>
                    <div class="col-auto">
                        {{-- if you are the owner of the post, you can edit or delete this post --}}
                        @if (Auth::user()->id === $post->user->id)
                            <div class="dropdown">
                                <button class="btn btn-sm shadow-one" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <div class="dropdown-menu">
                                    <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                        <i class="fa-regular fa-pen-to-square"></i> Edit
                                    </a>
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{ $post->id }}">
                                        <i class="fa-regular fa-trash-can"></i>Delete
                                    </button>
                                </div>
                                @include('users.posts.contents.modals.delete')
                            </div>
                        @else
                            {{-- if you are not the owner of the post, show a follow/unfollow button. to be discussed soon. --}}
                            {{-- show follow button for now --}}
                            @if ($user->isFollowed())
                            <form action="{{ route('follow.destroy', $user->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
                            </form>
                            @else
                            <form action="{{ route('follow.store'),$user->id }}" method="post" class="d-inline">
                                @csrf
                                <button type="submit" class="border-0 bg-transparent p-0 text-primary">Follow</button>
                            </form>
                            @endif
  
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body w-100">
                {{-- {{ heart button + no. of like + categories }} --}}
                <div class="row align-item-center">
                    <div class="col-auto">
                        @if ($post->isLiked())
                        <form action="{{ route('like.destroy', $post->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm shadow-none p-0 "><i class="fa-solid fa-heart text-danger"></i></button>
                        </form>
                        @else
                            <form action="{{ route('like.store', $post->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm shadow-none p-0"><i class="fa-solid fa-heart"></i></button>
                            </form>
                        @endif
                    </div>
                    <div class="col-auto px-0">
                        <span>{{ $post->likes->count() }}</span>
                    </div>
                    <div class="col text-end">
                        @forelse ($post->categoryPost as $category_post)
                            <span class="badge bg-secondary bg-opacity-50">{{ $category_post->category->name }}</span>
                        @empty
                            <div class="badge bg-dark text-wrap">Uncategorized</div>
                        @endforelse
                    </div>
                </div>

                {{-- owner + description --}}
                <a href="{{route('profile.show', $post->user->id)}}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>&nbsp;
                <p class="d-inline fw-light">{{ $post->description }}</p>
                <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

                {{-- commments --}}
                <div class="mt-4">
                    <form action="{{ route('comment.store' , $post->id) }}" method="post">
                        @csrf

                        <div class="input-group">
                            <textarea name="comment_body{{ $post->id }}"  rows="1" class="form-control form-control-sm" placeholder="Add a comment...">{{ old('comment_body' . $post->id) }}</textarea>
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                        </div>
                        @error('comment_body' . $post->id)
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </form>


                    {{-- show all the comments here --}}
                    @if ($post->comments->isNotEmpty())
                        <ul class="list-group mt-2">
                            @foreach ($post->comments as $comment)
                            <li class="list-group-item border-0 p-0 mb-2">
                                <a href="{{route('profile.show', $post->user->id)}}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                           
                            &nbsp;
                            <p class="d-inline fw-light">{{ $comment->body }}</p>

                            <form action="{{route('comment.destroy', $comment->id)}}" method="post">
                                @csrf
                                @method('DELETE')

                                <span class="text-uppercase text-muted xsmall">{{ date('M d,Y' , strtotime($comment->created_at)) }}</span>

                                {{-- if the auth user is the ownwe of the comment, show a delete button. --}}
                                @if (Auth::user()->id === $comment->user->id)
                                &middot;
                                <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                    
                                @endif
                            </form>
                        </li>
                            @endforeach
                        </ul>   
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection