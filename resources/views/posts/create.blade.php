<x-layouts.app-layout>
    <x-slot:title>
        Tasks
    </x-slot>
    <div class="mx-6">
        <div class="flex items-center justify-between py-1 px-2">
            <h1 class="text-3xl font-bold mb-4">New Post</h1>
        </div>
        <div class="px-2 container">
            <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <x-fieldset title="Title" name="title">
                    <input type="text" id="title" name="title" class="input w-full" placeholder="Type here" />
                </x-fieldset>

                <x-fieldset title="Content" name="content">
                    <textarea id="content" name="content" class="input !h-auto w-full" placeholder="Content..." rows="10"></textarea>
                </x-fieldset>

                <x-fieldset title="Status" name="status">
                    <select class="select" id="status" name="status">
                        <option disabled>Status</option>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }} defaultSelected>
                            Active
                        </option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </x-fieldset>

                <x-fieldset title="Image" name="image">
                    <input type="file" id="image" name="image" class="file-input" accept="image/*"
                        maxlength="2097152" />
                    <small class="text-gray-500">Max size: 2MB</small>
                </x-fieldset>

                <x-fieldset title="Tags" name="tags">
                    <select class="select !h-auto" id="tags" name="tags[]" multiple>
                        <option disabled>Tags</option>
                        @foreach ($tags as $id => $tag)
                            <option value="{{ $id }}"
                                {{ in_array($tag, old('tags', [])) ? 'selected' : '' }}>
                                {{ $tag }}
                            </option>
                        @endforeach
                    </select>
                    <label for="tags" class="label">Ctrl + click to multiselect</label>
                </x-fieldset>
                
                <button type="submit" class="btn btn-primary mt-4">Create</button>

            </form>
        </div>
    </div>
</x-layouts.app-layout>
