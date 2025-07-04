<a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-ghost text-success p-2 rounded-l-full">
    <x-icons.view class="w-5 h-5" />
</a>
<a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-ghost text-info p-2">
    <x-icons.edit class="w-5 h-5" />
</a>
<button type="button" data-post-id="{{ $post->id }}"
    class="delete-btn btn btn-sm btn-ghost text-error p-2 rounded-r-full">
    <x-icons.delete class="w-5 h-5" />
</button>
