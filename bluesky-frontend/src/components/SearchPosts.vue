<template>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Title -->
        <h1 class="text-3xl font-bold text-center mb-6">🔍 BlueSky Post Explorer</h1>

        <!-- Search & Filters -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <!-- Search Input -->
                <input v-model="query" @keyup.enter="resetSearch" placeholder="Search posts..."
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300" />
                <button @click="resetSearch"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    Search
                </button>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-4 mt-4">

                <!-- Date Picker Since -->
                <div class="w-full md:w-auto">
                    <label class="block text-gray-700 mb-2">Date from</label>
                    <VueDatePicker @change="resetSearch" v-model="since" placeholder="Select a date"
                        class="p-2 border rounded-lg w-full md:w-auto" />
                </div>

                <!-- Date Picker Until -->
                <div class="w-full md:w-auto">
                    <label class="block text-gray-700 mb-2">Date until</label>
                    <VueDatePicker @change="resetSearch" v-model="until" placeholder="Select a date"
                        class="p-2 border rounded-lg w-full md:w-auto" />
                </div>

                <!-- Sorting -->
                <div class="w-full md:w-auto">
                    <label class="block text-gray-700 mb-2">Order by</label>
                    <select v-model="sortBy" @change="resetSearch" class="p-2 border rounded-lg w-full md:w-auto" style="margin-top: 0.5rem">
                        <option value="latest">Most Recent</option>
                        <option value="top">Top positioned</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div v-if="loading && page === 1" class="text-center text-gray-500">Loading...</div>

        <!-- Error Message -->
        <div v-if="error" class="text-center text-red-500">{{ error }}</div>

        <!-- Post List -->
        <div v-if="posts.length" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="post in posts" :key="post.cid"
                class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition">

                <p class="text-gray-700 mb-2">{{ post.record.text }}</p>
                <small class="text-gray-500">By: {{ post.author.displayName }} | {{ post.record.createdAt }}</small>

                <div class="mt-2 flex items-center" style="justify-content: space-evenly">
                    <div class="tags" v-if="post.record.facets">
                        <span v-for="(facet, facetIndex) in post.record.facets" :key="facetIndex">
                            <span v-for="(feature, featureIndex) in facet.features" :key="featureIndex"
                                class="text-sm text-blue-500">
                                #{{ feature.tag || 'General' }}
                            </span>
                            <span v-if="facetIndex < post.record.facets.length - 1"> | </span>
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <span class="text-sm text-gray-400">❤️ Likes: {{ post.likeCount }}</span>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-400">💬 Replies: {{ post.replyCount }}</span>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-400">🔁 Reposts: {{ post.repostCount }}</span>
                </div>
                <div class="mt-4">
                    <span class="text-sm text-gray-400">🔗 Quotes: {{ post.quoteCount }}</span>
                </div>
            </div>
        </div>

        <!-- Infinite Scroll Loader -->
        <div v-if="loading && page > 1" class="text-center mt-4">Loading more posts...</div>
    </div>
</template>

<script>
import axios from 'axios';
import VueDatePicker from '@vuepic/vue-datepicker';

export default {
    components: { VueDatePicker },
    data() {
        return {
            query: '',
            posts: [],
            loading: false,
            error: null,
            page: 1,
            sortBy: 'latest',
            hasMore: true,
            since: null,
            until: null,
            isFetching: false, // Add this flag to prevent multiple fetch requests
        };
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        async fetchPosts() {
            if (!this.query || !this.hasMore || this.isFetching) return;

            this.loading = true;
            this.error = null;
            this.isFetching = true; // Set the flag to true when fetching starts

            console.log(this.tag, this.mentions, this.since, this.until);

            try {
                const response = await axios.get('http://127.0.0.1:8000/search', {
                    params: {
                        q: this.query,
                        page: this.page,
                        sort: this.sortBy,
                        limit: 100,
                        since: this.since,
                        until: this.until,
                    },
                });
                console.log('Request URL:', response.config.url);
                console.log('Request Params:', response.config.params);
                console.log(response.data.posts);
                if (response.data.posts.length > 0) {
                    this.posts = [...this.posts, ...response.data.posts];
                    this.page++;
                } else {
                    this.error = 'No posts match your search criteria.';
                    this.hasMore = false;
                }
            } catch (err) {
                this.error = 'Failed to fetch posts.';
            } finally {
                this.loading = false;
                this.isFetching = false; // Reset the flag when fetching ends
            }
        },
        resetSearch() {
            this.posts = [];
            this.page = 1;
            this.hasMore = true;
            this.fetchPosts();
        },
        handleScroll() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
                this.fetchPosts();
            }
        },
    },
};
</script>

<style scoped>
button {
    transition: all 0.3s;
}

button:hover {
    opacity: 0.8;
}

input,
select {
    transition: all 0.3s;
}

input:focus,
select:focus {
    border-color: #646cff;
    box-shadow: 0 0 0 3px rgba(100, 108, 255, 0.3);
}

.bg-white {
    background-color: #ffffff;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-2xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.transition {
    transition: all 0.3s;
}

.text-gray-500 {
    color: #6b7280;
}

.text-gray-700 {
    color: #374151;
}

.text-gray-400 {
    color: #9ca3af;
}

.text-blue-500 {
    color: #3b82f6;
}

.text-red-500 {
    color: #ef4444;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.p-2 {
    padding: 0.5rem;
    height: fit-content;
}

.p-3 {
    padding: 0.75rem;
}

.p-4 {
    padding: 1rem;
}

.p-6 {
    padding: 1.5rem;
}

.mb-2 {
    display: flex;
    margin-left: 0.5rem;
    align-items: start;
}

.mb-4 {
    margin-bottom: 1rem;
}

.mb-6 {
    margin-bottom: 1.5rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-4 {
    margin-top: 1rem;
}

.mt-6 {
    margin-top: 1.5rem;
}

.mx-auto {
    margin-left: auto;
    margin-right: auto;
}

.max-w-4xl {
    max-width: 56rem;
}

.grid {
    display: grid;
}

.grid-cols-1 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
}

.md\:grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.gap-4 {
    gap: 1rem;
}

.gap-6 {
    gap: 1.5rem;
}

.flex {
    display: flex;
}

.flex-col {
    flex-direction: column;
}

.md\:flex-row {
    flex-direction: row;
}

.items-center {
    align-items: center;
}

.items-start {
    align-items: flex-start;
}

.justify-between {
    justify-content: space-between;
}

.w-full {
    width: 100%;
}

.md\:w-auto {
    width: auto;
}

.text-center {
    text-align: center;
}

.font-bold {
    font-weight: 700;
}

.font-semibold {
    font-weight: 600;
}

.text-3xl {
    font-size: 1.875rem;
    line-height: 2.25rem;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.text-xl {
    font-size: 1.25rem;
    line-height: 1.75rem;
}

.rounded {
    border-radius: 0.25rem;
}

.border {
    border-width: 1px;
}

.focus\:ring {
    box-shadow: 0 0 0 3px rgba(100, 108, 255, 0.3);
}

.focus\:ring-blue-300 {
    box-shadow: 0 0 0 3px rgba(100, 108, 255, 0.3);
}

.bg-blue-500 {
    background-color: #3b82f6;
}

.text-white {
    color: #ffffff;
}

.hover\:bg-blue-600:hover {
    background-color: #2563eb;
}

.transition {
    transition: all 0.3s;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .flex-col {
        flex-direction: column;
    }

    .md\:flex-row {
        flex-direction: column;
    }

    .md\:grid-cols-2 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .md\:w-auto {
        width: 100%;
    }
}

@media (min-width: 769px) {
    .md\:flex-row {
        flex-direction: row;
    }

    .md\:grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .md\:w-auto {
        width: auto;
    }
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tags span:last-child::after {
    content: '';
}
</style>