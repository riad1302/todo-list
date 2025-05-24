<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import { computed } from 'vue';

// Props from the parent (optional post data for editing)
const { props } = usePage();
const post = props.post ?? {
    title: '',
    content: '',
    status: 1,
};

const isEdit = computed(() => !!post.id);

const form = useForm({
    title: post.title,
    content: post.content,
    status: post.status,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: isEdit.value ? 'Edit Post' : 'Create Post',
        href: isEdit.value ? `/posts/${post.id}/edit` : '/posts/create',
    },
];

const submit = () => {
    const url = isEdit.value ? `/posts/${post.id}` : '/posts';

    if (isEdit.value) {
        form.put(url, {
            preserveScroll: true,
            onSuccess: () => {
                // maybe show success message or redirect
            },
        });
    } else {
        form.post(url, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            },
        });
    }
};

</script>

<template>
    <Head :title="isEdit ? 'Edit Post' : 'Create Post'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block mb-1">Title</label>
                    <input v-model="form.title" class="w-full p-2 border rounded" />
                    <div v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</div>
                </div>

                <div>
                    <label class="block mb-1">Content</label>
                    <textarea v-model="form.content" class="w-full p-2 border rounded"></textarea>
                    <div v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</div>
                </div>

                <div>
                    <label class="block mb-1">Status</label>
                    <select v-model="form.status" class="w-full p-2 border rounded">
                        <option :value="1">Active</option>
                        <option :value="0">Inactive</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    :disabled="form.processing"
                >
                    {{ isEdit ? 'Update' : 'Create' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
