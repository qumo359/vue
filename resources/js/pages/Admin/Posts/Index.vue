<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
// import AdminSidebar from '@/components/AdminSidebar.vue';
// import ResultMessages from '@/components/ResultMessages.vue';
import type { BreadcrumbItem } from '@/types';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/posts',
    },
];

const props = defineProps({
    paginator: {
        type: Object,
        required: true,
    },
    flash: {
        type: Object,
        default: () => ({}),
    },
});

const destroy = (post) => {
    console.log('Функция destroy вызвана', post.id);

    if (confirm(`Вы уверены, что хотите удалить пост? "${post.title}"?`)) {
        form.delete(route('admin.posts.destroy', post.id));
    }
};

const posts = computed(() => props.paginator.data);
</script>

<template>
    <AdminLayout title="Управление Статьями" :breadcrumbs="breadcrumbs">
        <div class="container p-3">
            <nav class="flex gap-2">
                <Link
                    class="inline-flex items-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900"
                    :href="route('admin.posts.create')"
                >
                    Написать
                </Link>
                <Link
                    v-if="flash.deleted_id"
                    class="inline-flex items-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-700 focus:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-900"
                    :href="route('admin.posts.restore', flash.deleted_id)"
                >
                    Отменить удаление
                </Link>
            </nav>
        </div>

        <div class="data-table-container rounded-md border border-gray-600 bg-gray-800 shadow-md">
            <table class="data-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Автор</th>
                    <th>Категория</th>
                    <th>Заголовок</th>
                    <th>Дата публикации</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="post in posts" :key="post.id" :style="{ backgroundColor: !post.is_published ? 'grey' : '' }">
                    <td>{{ post.id }}</td>
                    <td>{{ post.user.name }}</td>
                    <td>{{ post.category.title }}</td>
                    <td>
                        <Link :href="route('admin.posts.edit', post.id)">{{ post.title }}</Link>
                    </td>
                    <td>
                        {{
                            post.published_at
                                ? new Date(post.published_at).toLocaleDateString() + ' ' + new Date(post.published_at).toLocaleTimeString()
                                : ''
                        }}
                    </td>
                    <td>
                        <div class="flex">
                            <Link :href="route('admin.posts.edit', post.id)">
                                <button class="edit-button">Редактировать</button>
                            </Link>
                            <button class="delete-button" @click="destroy(post)">Удалить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-if="props.paginator.total > props.paginator.per_page" class="mt-3">
            <nav aria-label="Page navigation">
                <div class="flex justify-center">
                    <div v-for="(link, key) in props.paginator.links" :key="key">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                        'relative z-0 inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500',
                        {'bg-indigo-500 text-white hover:bg-indigo-600': link.active},
                        {'cursor-default text-gray-700': !link.url},
                        key === 0 ? 'rounded-l-md' : '',
                        key === props.paginator.links.length - 1 ? 'rounded-r-md' : '',
                        key > 0 ? 'ml-px' : ''
                    ]"
                            v-html="link.label"
                        ></Link>
                        <span
                            v-else
                            :class="[
                        'relative z-0 inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 cursor-default',
                        {'bg-indigo-500 text-white': link.active},
                        key === 0 ? 'rounded-l-md' : '',
                        key === props.paginator.links.length - 1 ? 'rounded-r-md' : '',
                        key > 0 ? 'ml-px' : ''
                    ]"
                            v-html="link.label"
                        ></span>
                    </div>
                </div>
            </nav>
        </div>
    </AdminLayout>
</template>

<style scoped>
.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.data-table th {
    background-color: #121212;
    font-weight: 600;
    color: #d4ef1f;
}

.data-table tbody tr:nth-child(even) {
    background-color: #121212;
}

.edit-button,
.delete-button {
    background-color: #3182ce;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
    margin-right: 5px;
}

.delete-button {
    background-color: #e53e3e;
}

.edit-button:hover {
    background-color: #2c5282;
}

.delete-button:hover {
    background-color: #c53030;
}
</style>
