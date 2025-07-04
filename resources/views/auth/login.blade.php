<x-layouts.auth-layout>
    <x-slot:title>
        Login
    </x-slot>
    <form action="{{ route('login') }}" method="post">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Login</legend>

            <label class="label" for="email">Email</label>
            <input id="email" type="email" name="email" class="input" placeholder="Email" />
            @error('email')
                <label class="label text-error" for="email"> {{ $message }} </label>
            @enderror

            <label class="label">Password</label>
            <input type="password" name="password" class="input" placeholder="Password" />
            @error('password')
                <label class="label text-error" for="password"> {{ $message }} </label>
            @enderror

            <button type="submit" class="btn btn-neutral mt-4">Login</button>
        </fieldset>
    </form>
</x-layouts.auth-layout>
