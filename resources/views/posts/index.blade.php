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
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="posts-table" class="table table-zebra">
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
                </tbody>
            </table>
            <div class="mt-4 px-4">
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
