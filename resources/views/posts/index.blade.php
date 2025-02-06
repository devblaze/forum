<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    @vite('resources/css/app.css')

    <!-- AlpineJS for handling dark mode -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
<div class="container mx-auto my-10">
    <!-- Dark Mode Toggle -->
    <div class="text-right mb-6">
        <button
            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md shadow"
            x-on:click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
        >
            Toggle Dark Mode
        </button>
    </div>

    <h1 class="text-4xl font-bold text-center mb-8">Posts Listing</h1>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form method="GET" action="{{ route('posts.search') }}" class="mb-6">
            <div class="relative">
                <input
                    type="text"
                    name="query"
                    placeholder="Search..."
                    class="border rounded-lg px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                >
                <button
                    type="submit"
                    class="absolute top-0 right-0 mt-2 mr-2 text-gray-500 dark:text-gray-300 hover:text-blue-500 dark:hover:text-blue-400"
                >
                    🔍
                </button>
            </div>
        </form>
    @if ($posts->count())
            <ul>
                @foreach ($posts as $post)
                    <li class="mb-4">
                        <a href="{{ route('posts.show', $post->id) }}"
                           class="text-blue-500 dark:text-blue-400 hover:underline text-lg">
                            {{ $post->title }}
                        </a>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            {{ $post->created_at->diffForHumans() }}
                        </p>
                    </li>
                @endforeach
            </ul>
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-center">
                No posts available.
            </p>
        @endif
    </div>
</div>
</body>
</html>
