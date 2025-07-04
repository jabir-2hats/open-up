<button type="button" class="btn btn-circle btn-sm comments-btn" data-post-id="{{ $post->id }}">
    {{ $post->comments_count ?? 0 }}
</button>
