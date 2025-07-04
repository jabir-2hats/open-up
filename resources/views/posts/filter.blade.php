<form id="filter-form" action="{{ route('posts.index') }}" method="get">
    <h1 class="text-xl">Filter</h1>

    <fieldset class="fieldset">
        <legend class="fieldset-legend">Title</legend>
        <div class="join">
            <input type="text" id="title" name="title" class="input w-full join-item" placeholder="Search a title"
                value="{{ old('title', request('title')) }}" />
            <select name="title_order" class="select join-item w-fit">
                <option value="">Sort</option>
                <option value="asc" {{ request('title_order') == 'asc' ? 'selected' : '' }}>
                    Asc
                </option>
                <option value="desc" {{ request('title_order') == 'desc' ? 'selected' : '' }}>
                    Desc
                </option>
            </select>
        </div>
        @error('title')
            <label class="label text-error" for="title"> {{ $message }} </label>
        @enderror
    </fieldset>

    <fieldset class="fieldset">
        <legend class="fieldset-legend">Author</legend>
        <div class="join">
            <input type="text" id="author" name="author" class="input w-full join-item"
                placeholder="Search an author" value="{{ old('author', request('author')) }}" />
            <select name="author_order" class="select join-item w-fit">
                <option value="">Sort</option>
                <option value="asc" {{ request('author_order') == 'asc' ? 'selected' : '' }}>
                    Asc
                </option>
                <option value="desc" {{ request('author_order') == 'desc' ? 'selected' : '' }}>
                    Desc
                </option>
            </select>
        </div>
        @error('author')
            <label class="label text-error" for="author"> {{ $message }} </label>
        @enderror
    </fieldset>

    <fieldset class="fieldset">
        <legend class="fieldset-legend">Published At</legend>
        <div class="join">
            <input type="date" id="published_at" name="published_at" class="input w-full join-item"
                value="{{ old('published_at', request('published_at')) }}" />
            <select name="published_at_order" class="select join-item w-fit">
                <option value="">Sort</option>
                <option value="asc" {{ request('published_at_order') == 'asc' ? 'selected' : '' }}>
                    Asc
                </option>
                <option value="desc" {{ request('published_at_order') == 'desc' ? 'selected' : '' }}>
                    Desc
                </option>
            </select>
        </div>
        @error('published_at')
            <label class="label text-error" for="published_at"> {{ $message }} </label>
        @enderror
    </fieldset>

    <fieldset class="fieldset">
        <legend class="fieldset-legend">Status</legend>
        <select class="select w-full" id="status" name="status">
            <option value="">Status</option>
            <option value="Active" {{ old('status', request('status')) == 'Active' ? 'selected' : '' }}>
                Active
            </option>
            <option value="Inactive" {{ old('status', request('status')) == 'Inactive' ? 'selected' : '' }}>
                Inactive
            </option>
        </select> @error('status')
            <label class="label text-error" for="status"> {{ $message }} </label>
        @enderror
    </fieldset>

    <fieldset class="fieldset">
        <legend class="fieldset-legend">No. Of Comments</legend>
        <div class="join">
            <input type="number" id="comments_count" name="comments_count" class="input w-full join-item"
                value="{{ old('comments_count', request('comments_count')) }}" />
            <select name="comments_count_order" class="select join-item w-fit">
                <option value="">Sort</option>
                <option value="asc" {{ request('comments_count_order') == 'asc' ? 'selected' : '' }}>
                    Asc
                </option>
                <option value="desc" {{ request('comments_count_order') == 'desc' ? 'selected' : '' }}>
                    Desc
                </option>
            </select>
        </div>
        @error('comments_count')
            <label class="label text-error" for="comments_count"> {{ $message }} </label>
        @enderror
    </fieldset>

    <fieldset class="fieldset">
        <legend class="fieldset-legend">Tags</legend>
        <select name="tags[]" id="tags" class="select w-full !h-auto" multiple>
            @php
                $selectedTags = request('tags', []);
                if (!is_array($selectedTags)) {
                    $selectedTags = [$selectedTags];
                }
            @endphp
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </fieldset>

    <button type="submit" class="btn btn-primary mt-2">Apply</button>
    <a href="{{ route('posts.index') }}" class="btn btn-outline btn-neutral mt-2">Reset</a>
</form>
