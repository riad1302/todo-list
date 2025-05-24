<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import FlashMessage from '@/components/FlashMessage.vue';

const { props } = usePage();

const search = ref(props.filters?.search || '');
const posts = computed(() => props.posts);

const startIndex = computed(() => {
    const currentPage = posts.value?.current_page ?? 1;
    const perPage = posts.value?.per_page ?? 10;
    return (currentPage - 1) * perPage;
});

let searchTimeout: ReturnType<typeof setTimeout>;

watch(search, (value) => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            '/posts',
            { search: value },
            {
                replace: true,
            }
        );
    }, 1000);
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];
</script>


<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative flex flex-col w-full h-full overflow-scroll text-gray-700 bg-white shadow-md rounded-xl bg-clip-border"
            >
                <!-- Search + Create Post Button -->
                <div class="flex justify-between mb-4">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Search by title..."
                        class="w-full md:w-1/3 p-2 border border-gray-300 rounded"
                    />
                    <Link
                        href="/posts/create"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition"
                    >
                        Create Post
                    </Link>
                </div>

                <!-- Table of Posts -->
                <table class="w-full text-left table-auto min-w-max">
                    <thead>
                    <tr>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">#</th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">Title</th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">Content</th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50">Status</th>
                        <th class="p-4 border-b border-blue-gray-100 bg-blue-gray-50"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(post, index) in posts.data" :key="post.id">
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="text-sm text-blue-gray-900">{{ startIndex + index + 1 }}</p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="text-sm text-blue-gray-900">{{ post.title }}</p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="text-sm text-blue-gray-900">
                                {{ post.content.length > 20 ? post.content.slice(0, 20) + '...' : post.content }}
                            </p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="text-sm text-blue-gray-900">{{ post.status == 1 ? 'Active' : 'Inactive' }}</p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <Link
                                :href="`/posts/${post.id}/edit`"
                                class="text-sm font-medium text-blue-gray-900 hover:underline"
                            >
                                Edit
                            </Link>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <Pagination :elements="posts" />
            </div>
        </div>
    </AppLayout>
</template>
