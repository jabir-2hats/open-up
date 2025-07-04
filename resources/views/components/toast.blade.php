@props(['type' => 'info', 'message' => ''])

<div class="badge badge-{{ $type }} badge-soft transition-opacity duration-500 w-fit">
    {{ $message }}
</div>
