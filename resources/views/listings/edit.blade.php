<x-layout>
    <x-card class="mx-auto mt-24 max-w-3xl p-10">
        <header class="text-center">
            <h2 class="mb-1 text-2xl font-bold uppercase">
                Edit Job
            </h2>
            <p class="mb-4">Edit: {{ $listing->title }}</p>
        </header>

        <form method="POST" action="/listings/{{ $listing->id }}" enctype="multipart/form-data"
            class="grid grid-cols-2 gap-4">
            @csrf
            @method('PUT')
            {{-- Company name --}}
            <div class="mb-6">
                <label for="company" class="mb-2 inline-block text-lg">Company Name</label>
                <input type="text" class="w-full rounded border border-gray-200 p-2" name="company"
                    value="{{ $listing->company }}" />
                @error('company')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Title --}}
            <div class="mb-6">
                <label for="title" class="mb-2 inline-block text-lg">Job Title</label>
                <input type="text" class="w-full rounded border border-gray-200 p-2" name="title"
                    value="{{ $listing->title }}" placeholder="Example: Senior Laravel Developer" />
                @error('title')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div class="mb-6">
                <label for="location" class="mb-2 inline-block text-lg">Job Location</label>
                <input type="text" class="w-full rounded border border-gray-200 p-2" name="location"
                    value="{{ $listing->location }}" placeholder="Example: Remote, Boston MA, etc" />
                @error('location')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tags --}}
            <div class="mb-6">
                <label for="tags" class="mb-2 inline-block text-lg">
                    Tags (Comma Separated)
                </label>
                <input type="text" class="w-full rounded border border-gray-200 p-2" name="tags"
                    value="{{ $listing->tags }}" placeholder="Example: Laravel, Backend, Postgres, etc" />
                @error('tags')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Website --}}
            <div class="mb-6">
                <label for="website" class="mb-2 inline-block text-lg">
                    Website/Application URL
                </label>
                <input type="text" class="w-full rounded border border-gray-200 p-2" name="website"
                    value="{{ $listing->website }}" />
                @error('website')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label for="email" class="mb-2 inline-block text-lg">Contact Email</label>
                <input type="text" class="w-full rounded border border-gray-200 p-2" name="email"
                    value="{{ $listing->email }}" />
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Logo --}}
            <div class="mb-6">
                <label for="logo" class="mb-2 inline-block text-lg">
                    Company Logo
                </label>
                <input type="file" class="w-full rounded border border-gray-200 p-2" name="logo" />

                <img class="mb-6 mr-6 w-48"
                    src="{{ $listing->logo ? asset('storage/' . $listing->logo) : asset('images/no-image.png') }}"
                    alt="" />
                {{-- @dd(asset('storage/' . $listing->logo)); --}}
                @error('logo')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="mb-2 inline-block text-lg">
                    Job Description
                </label>
                <textarea class="w-full rounded border border-gray-200 p-2" name="description" rows="10"
                    placeholder="Include tasks, requirements, salary, etc">
                    {{ $listing->description }}
                </textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button class="bg-laravel rounded px-4 py-2 text-white hover:bg-black">
                    Update Job
                </button>

                <a href="/" class="ml-4 text-black"> Back </a>
            </div>
        </form>
    </x-card>
</x-layout>
