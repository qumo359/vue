<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import BlogPost from '@/components/BlogPost.vue';
import '@vuepic/vue-datepicker/dist/main.css';
//import { ref } from 'vue';

defineProps({ posts: BlogPost });

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
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
                <BlogPost v-for="post in posts" :key="post.id" :title="post.title" />
                <!--                <div v-for="post in posts" :key="post.id">-->
                <!--                    {{ post }}-->
                <!--                    <span @click="update()">update</span>-->
                <!--                </div>-->
            </div>
        </div>
    </AppLayout>
</template>
