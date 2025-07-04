@props(['comment', 'post'])

<div class="border rounded p-3 bg-gray-50">
    <div class="flex items-center mb-1">
        <span class="font-semibold mr-2">{{ $comment->user->name ?? 'Anonymous' }}</span>
        <span class="text-xs text-gray-400">{{ $comment->created_at }}</span>
        @can('update', $comment)
            <button class="edit-btn btn btn-xs btn-ghost btn-circle" data-comment-id="{{ $comment->id }}" data-comment-content="{{ $comment->content }}">
                <x-icons.edit class="w-3 h-3" />
            </button>
        @endcan
        @can('delete', $comment)
            <button class="delete-btn btn btn-xs btn-ghost btn-circle" data-comment-id="{{ $comment->id }}">
                <x-icons.delete class="w-3 h-3" />
            </button>
        @endcan
    </div>
    <div class="text-gray-800">{{ $comment->content }}</div>
</div>
