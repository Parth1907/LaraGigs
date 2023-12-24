<x-layout>
    <x-card class="mx-auto mt-12 max-w-2xl p-10">
        <header class="text-center">
            <h2 class="mb-1 text-2xl font-bold uppercase">
                Login
            </h2>
            <p class="mb-4">Login to your account</p>
        </header>

        <form method="POST" action="/users/authenticate">
            @csrf
            <div class="mb-6">
                <label for="email" class="mb-2 inline-block text-lg">Email</label>
                <input type="email" class="w-full rounded border border-gray-200 p-2" name="email"
                    value="{{ old('email') }}" />
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="mb-2 inline-block text-lg">
                    Password
                </label>
                <input type="password" class="w-full rounded border border-gray-200 p-2" name="password" />
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button type="submit" class="bg-laravel rounded px-4 py-2 text-white hover:bg-black">
                    Sign In
                </button>
            </div>

            <div class="mt-8">
                <p>
                    Dont have an account?
                    <a href="/register" class="text-laravel">Register</a>
                </p>
            </div>
        </form>
    </x-card>
</x-layout>
