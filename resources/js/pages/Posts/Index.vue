<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import BlogPost from '@/components/BlogPost.vue';
import '@vuepic/vue-datepicker/dist/main.css';
import { defineProps } from 'vue';
import type { BlogPost as BlogPostType } from '@/Models/BlogPost';


const props = defineProps({
    posts: {
        type: Object as () => {
            data: Array<BlogPostType>;
        },
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin'
    }
];

const update = () => {
    alert(1);
};
</script>

<template>
    <Head title="Posts" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <BlogPost
                    v-for="post in posts.data"
                    :key="post.id"
                    :post="post"
                    :hasLikedPost="post.has_liked"
                    :postLikesCount="post.likes_count"
                />
            </div>
        </div>
    </AppLayout>
</template>
