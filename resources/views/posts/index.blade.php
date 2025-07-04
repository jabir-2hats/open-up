<x-layouts.app-layout>
    <x-slot:title>
        Posts
    </x-slot>
    <div class="mx-6">
        <div class="flex items-center justify-between py-1 px-2">
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-bold mb-4">Posts</h1>
                @if (session('success'))
                    <x-toast type="success" message="{{ session('success') }}" />
                @endif
                @if (session('error'))
                    <x-toast type="error" message="{{ session('error') }}" />
                @endif
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create +</a>
                <div class="drawer drawer-end">
                    <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
                    <div class="drawer-content">
                        <!-- Page content here -->
                        <label for="my-drawer-4" class="drawer-button btn btn-primary btn-outline">Filter</label>
                    </div>
                    <div class="drawer-side">
                        <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
                        <ul class="menu bg-base-200 text-base-content min-h-full w-100 p-4">
                            @include('posts.filter', ['tags' => $tags])
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <!-- head -->
                <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Published at</th>
                        <th>Status</th>
                        <th>Comments</th>
                        <th>Tags</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>
                                <a href="{{ route('posts.show', ['post' => $post]) }}"
                                    class="badge badge-soft badge-primary cursor-pointer hover:bg-base-300">
                                    {{ $post->title }}
                                </a>
                            </td>
                            <td>{{ $post->author->name }}</td>
                            <td>{{ $post->published_at }}</td>
                            <td
                                class="badge badge-soft {{ $post->status === 'Active' ? 'badge-success' : 'badge-error' }}">
                                {{ $post->status }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-circle btn-sm comments-btn"
                                    data-post-id="{{ $post->id }}">
                                    {{ $post->comments_count ?? 0 }}
                                </button>
                            </td>
                            <td>
                                @if ($post->tags->isEmpty())
                                    <span class="text-gray-500">No tags</span>
                                @else
                                    @foreach ($post->tags as $tag)
                                        <span class="badge badge-soft badge-info">{{ $tag->name }}</span>
                                    @endforeach
                                @endif
                            </td>

                            {{-- actions --}}
                            <td class="join rounded-full">
                                <a href="{{ route('posts.show', $post) }}"
                                    class="join-item btn btn-sm btn-ghost text-success p-2 rounded-l-full">
                                    <x-icons.view class="w-5 h-5" />
                                </a>
                                <a href="{{ route('posts.edit', $post) }}"
                                    class="join-item btn btn-sm btn-ghost text-info p-2">
                                    <x-icons.edit class="w-5 h-5" />
                                </a>
                                <button type="button" data-post-id="{{ $post->id }}"
                                    class="delete-btn join-item btn btn-sm btn-ghost text-error p-2 rounded-r-full">
                                    <x-icons.delete class="w-5 h-5" />
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 px-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <x-modal id="delete_modal" title="Delete this post?">
        <p class="py-4">Press ESC key or click the button below to close</p>
        <x-slot:actions>
            <form action="" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">Delete</button>
            </form>
        </x-slot:actions>
    </x-modal>

    <!-- Comments Modal -->
    <x-modal id="comments_modal" title="Comments">
        <div id="comments_content" class="space-y-4 min-h-[80px] text-left"></div>
    </x-modal>

    @include('posts.scripts.index')

</x-layouts.app-layout>
