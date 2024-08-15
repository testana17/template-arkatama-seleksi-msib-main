<div>
    @if($date)
        @if(isset($user))
            <p class="m-0">
                {{ $date['formatted'] }}
            </p>
            <small class="text-muted">
                {{ $date['diff'] }} {{ $user !== null ? 'oleh ' . $user->name : '' }}
            </small>
        @else
            <p class="m-0">
                {{ $date['formatted'] }}
            </p>
            <small class="text-muted">
                {{ $date['diff'] }}
            </small>
        @endif
    @else
        <p class="m-0">
            -
        </p>
    @endif
</div>
