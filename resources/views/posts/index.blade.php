<x-layouts.app-layout>
    <x-slot:title>
        Posts
    </x-slot>
    <x-slot:head>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </x-slot>
    <div class="mx-6">
        <div class="flex items-center justify-between py-1 px-2">
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-bold">Posts</h1>
                <input type="text" name="dates" placeholder="Filter By Date Range"
                    class="input input-bordered w-full max-w-xs">
                <div class="flex items-center gap-4">
                    <div class="join">
                        <input type="number" id="comments_count" name="comments_count" placeholder="Comments Count" class="input w-fit join-item"
                            value="{{ old('comments_count') }}" />
                        <select name="comments_count_operator" id="comments_count_operator" class="select w-fit join-item">
                            <option value="=" defaultSelected>
                                =
                            </option>
                            <option value=">=">
                                >=
                            </option>
                            <option value="<=">
                                <=
                            </option>
                            <option value=">">
                                >
                            </option>
                            <option value="<">
                                <
                            </option>
                            <option value="!=">
                                !=
                            </option>
                        </select>
                    </div>
                    <select name="status" id="status" class="select w-fit">
                        <option value="">Status</option>
                        <option value="Active">
                            Active
                        </option>
                        <option value="Inactive">
                            Inactive
                        </option>
                    </select>
                    <select name="tags[]" id="tags" class="select min-w-xs" multiple="multiple"
                        data-placeholder="Filter By Tags">
                    </select>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline btn-error">Clear</a>
                </div>
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
        <div class="card rounded-box shadow p-4 m-4">
            <div class="overflow-x-auto">
                <table id="posts-table" class="!table !table-zebra">
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
