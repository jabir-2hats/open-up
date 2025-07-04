@props(['title', 'name'])
<fieldset class="fieldset">
    <legend class="fieldset-legend">{{ $title }}</legend>
    {{ $slot }}
    @error($name)
        <label class="label text-error" for="title"> {{ $message }} </label>
    @enderror
</fieldset>
