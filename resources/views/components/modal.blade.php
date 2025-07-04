@props(['id' => 'default_modal', 'title' => 'Modal Title'])
<dialog id="{{ $id }}" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <h3 class="text-lg font-bold mb-4">{{ $title }}</h3>
        {{ $slot }}
        <div class="modal-action">
            {{ $actions ?? '' }}
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>
