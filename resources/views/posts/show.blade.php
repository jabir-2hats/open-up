<x-layouts.app-layout>
    <x-slot:title>
        {{ $post->title }}
    </x-slot>
    <div class="max-w-2xl mx-auto my-8">
        <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 text-gray-600 text-sm">
            <div>
                <span>By <span class="font-semibold">{{ $post->author->name ?? 'Unknown' }}</span></span>
                <span class="mx-2">|</span>
                <span>{{ $post->published_at }}</span>
            </div>
            <div>
                @foreach ($post->tags as $tag)
                    <span
                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
        @if ($post->image_url)
            <div class="mb-6 flex justify-center">
                <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image"
                    class="rounded shadow max-h-96 w-auto" />
            </div>
        @endif
        <article class="prose prose-lg font-serif leading-relaxed text-lg mb-8">
            {!! nl2br(e($post->content)) !!}
        </article>
        <section class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Comments ({{ $post->comments->count() }})</h2>
            <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="content" rows="3" class="input w-full mb-2" placeholder="Add a comment...">{{ old('content') }}</textarea>
                @error('content')
                    <div class="text-error mb-2">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>

            <div class="space-y-4">
                @forelse($post->comments as $comment)
                    <x-comment :comment="$comment" :post="$post" />
                @empty
                    <div class="text-gray-500">No comments yet.</div>
                @endforelse
            </div>
        </section>
    </div>
    <!-- Delete Modal -->
    <x-modal id="delete_modal" title="Delete this comment?">
        <p class="py-4">Are you sure you want to delete this comment?</p>
        <x-slot:actions>
            <form action="{{ route('comments.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">Delete</button>
            </form>
        </x-slot:actions>
    </x-modal>

    <!-- Comments Modal -->
    <x-modal id="edit_modal" title="Edit Comment">
        <form id="edit_form" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <textarea id="edit_content" name="content" rows="3" class="input w-full mb-2" placeholder="Edit your comment...">{{ old('content') }}</textarea>
            @error('content')
                <div class="text-error mb-2">{{ $message }}</div>
            @enderror
        </form>
        <x-slot:actions>
            <button id="edit-submit-btn" class="btn btn-primary">Update Comment</button>
        </x-slot:actions>
    </x-modal>
    @include('posts.scripts.show')
</x-layouts.app-layout>
