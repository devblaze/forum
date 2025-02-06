<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    @vite('resources/css/app.css')

    <!-- AlpineJS for handling dark mode -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
<div class="container mx-auto my-10">
    <!-- Back Button -->
    <div class="mb-6">
        <a
            href="{{ route('home') }}"
            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md shadow hover:bg-gray-300 dark:hover:bg-gray-600"
        >
            ← Go Back
        </a>
    </div>

    <!-- Dark Mode Toggle -->
    <div class="text-right mb-6">
        <button
            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md shadow"
            x-on:click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
        >
            Toggle Dark Mode
        </button>
    </div>

    <!-- Post Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
            Posted {{ $post->created_at->diffForHumans() }} by User #{{ $post->author_id }}
        </p>
        <div class="text-gray-800 dark:text-gray-200">
            {{ $post->content }}
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6" x-data="infiniteScroll({ postId: {{ $post->id }} })" x-init="init()">
        <h2 class="text-2xl font-bold mb-4">Comments (<span x-text="totalComments"></span>)</h2>

        <!-- Comments List -->
        <ul>
            <template x-for="(comment, index) in comments" :key="comment.id">
                <li class="border-b last:border-none border-gray-200 dark:border-gray-700 py-4">
                    <p class="text-gray-800 dark:text-gray-200 mb-2" x-text="comment.content"></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Posted <span x-text="comment.created_at_diff"></span> by User #<span x-text="comment.user_id"></span>
                    </p>
                </li>
            </template>
        </ul>

        <!-- Loading Indicator -->
        <div x-show="isLoading" class="text-center mt-6">
            <p class="text-gray-500 dark:text-gray-300">Loading more comments...</p>
        </div>

        <!-- End of Comments -->
        <div x-show="!hasMoreComments && comments.length > 0" class="text-center mt-6">
            <p class="text-gray-500 dark:text-gray-400">No more comments to load.</p>
        </div>

        <!-- Empty State -->
        <div x-show="comments.length === 0 && !isLoading" class="text-center mt-6">
            <p class="text-gray-500 dark:text-gray-400">No comments yet.</p>
        </div>
    </div>
</div>

<script>
    function infiniteScroll({ postId }) {
        return {
            comments: [],
            totalComments: 0,
            page: 1,
            hasMoreComments: true,
            isLoading: false,

            async init() {
                // Fetch initial comments
                await this.loadComments();

                // Handle scrolling for infinite loading
                window.addEventListener('scroll', async () => {
                    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200 && this.hasMoreComments && !this.isLoading) {
                        await this.loadComments();
                    }
                });
            },

            async loadComments() {
                if (!this.hasMoreComments || this.isLoading) return;

                this.isLoading = true;

                try {
                    const response = await fetch(`/api/posts/${postId}/comments?page=${this.page}`);
                    const data = await response.json();

                    // Append only new comments to avoid duplicates
                    const newComments = data.comments.filter(
                        newComment => !this.comments.some(existingComment => existingComment.id === newComment.id)
                    );

                    this.comments.push(...newComments);

                    // Update total comments count and pagination state
                    this.totalComments = data.total;
                    this.page += 1;
                    this.hasMoreComments = data.hasMore;
                } catch (error) {
                    console.error('Error loading comments:', error);
                } finally {
                    this.isLoading = false;
                }
            }
        };
    }
</script>

</body>
</html>
