<x-layouts.app-layout>
    <x-slot:title>
        Tasks
    </x-slot>
    <div class="mx-6">
        <div class="flex items-center justify-between py-1 px-2">
            <h1 class="text-3xl font-bold mb-4">Edit Post</h1>
        </div>
        <div class="px-2 container">
            <form action="{{ route('posts.update', $post) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-fieldset title="Title" name="title">
                    <input type="text" id="title" name="title" class="input w-full" placeholder="Type here"
                        value="{{ $post->title }}" />
                </x-fieldset>

                <x-fieldset title="Content" name="content">
                    <textarea id="content" name="content" class="input !h-auto w-full" placeholder="Content..." rows="10">{{ $post->content }}</textarea>
                </x-fieldset>

                <x-fieldset title="Status" name="status">
                    <select class="select" id="status" name="status">
                        <option disabled>Status</option>
                        <option value="Active" {{ $post->status == 'Active' ? 'selected' : '' }} defaultSelected>
                            Active
                        </option>
                        <option value="Inactive" {{ $post->status == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </x-fieldset>

                @if ($post->image_url)
                    <span class="label">Current Image</span>
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $post->image_url) }}" alt="Post Image"
                            class="rounded shadow max-h-64 w-auto" />
                    </div>
                @endif

                <x-fieldset title="Image" name="image">
                    <input type="file" id="image" name="image" class="file-input" accept="image/*"
                        maxlength="2097152" />
                    <small class="text-gray-500">Max size: 2MB</small>
                </x-fieldset>

                <div class="mt-4 flex items-center gap-2">
                    <span class="text-sm" for="remove_image">Remove Image</span>
                    <input type="checkbox" id="remove_image" name="remove_image" class="checkbox mt-2 text-sm" />
                    @error('remove_image')
                        <label class="label text-error" for="remove_image"> {{ $message }} </label>
                    @enderror
                </div>

                <x-fieldset title="Tags" name="tags">
                    <select class="select !h-auto" id="tags" name="tags[]" multiple>
                        <option disabled>Tags</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="tags" class="label">Ctrl + click to multiselect</label>
                </x-fieldset>

                <button type="submit" class="btn btn-primary mt-4">Update</button>

            </form>
        </div>
    </div>
</x-layouts.app-layout>
