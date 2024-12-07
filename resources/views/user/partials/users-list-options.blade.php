<div class="input-group-prepend">
    <button type="button" class="btn btn-info btn-sm dropdown-toggle"
        data-toggle="dropdown" aria-expanded="false">
        {{ __('Options') }}
    </button>
    <div class="dropdown-menu" style="">
        <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">
            <i class="far fa-eye mr-1"></i>
            {{ __('View') }}
        </a>
        @can('user.update')
            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                <i class="far fa-edit mr-1"></i>
                {{ __('Edit') }}
            </a>
        @endcan
        @can('user.delete')
            @if ($user->id != 1)
                <button type="button" class="btn btn-link dropdown-item delete_user_btn">
                    <i class="far fa-trash-alt mr-1"></i>
                    {{ __('Delete') }}
                    <form action="{{ route('users.destroy', $user->id) }}" method="post" class="d-none">
                        @csrf
                        @method('delete')
                    </form>
                </button>
            @endif
        @endcan
    </div>
</div>

