<div class="card-header bg-white py-3">
    <div class="row alogn-items-center">
        <div class="col-auto">
            @if ($post->user->avatar)
                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle avatar-sm">
            @else
                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
            @endif
        </div>
        <div class="col ps-0">
            <a href="{{route('profile.show', $post->user->id)}}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
        </div>
        <div class="col-auto">
            <div class="dropdown">
                <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>

                {{-- if you are the owner of the post, you can edit or delete the post --}}
                @if (Auth::user()->id === $post->user->id)
                    <div class="dropdown-menu">
                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item text-primary">
                            <i class="fa-regular fa-pen-to-square"></i>Edit
                        </a>
                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{ $post->id }}">
                            {{-- modal is the pop up message of bootstrap --}}
                            {{-- data-bs-target will be match with the  id of modal fade --}}
                            <i class="fa-regular fa-trash-can"></i>Delete
                        </button>
                    </div>
                    @include('users.posts.contents.modals.delete')
                @else
                    {{-- if you are not the owner of the post, show an unfollow button. to be discussed soon. --}}
                    <div class="dropdown-menu">
                        <form action="{{ route('follow.destroy',$post->user->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="dropdown-item text-danger">Unfollow</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>