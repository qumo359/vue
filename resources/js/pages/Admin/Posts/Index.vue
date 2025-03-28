<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
// import ResultMessages from '@/components/ResultMessages.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

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

const posts = computed(() => props.paginator.data);
</script>

<template>
    <AdminLayout title="Управление Статьями">
        <template #sidebar>
            <AdminSidebar />
        </template>

        <ResultMessages />

        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <Link class="btn btn-primary" :href="route('admin.posts.create')">Написать</Link>
            <Link v-if="flash.deleted_id" class="btn btn-danger" :href="route('admin.posts.restore', flash.deleted_id)">Отменить удаление</Link>
        </nav>

        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Заголовок</th>
                        <th>Дата публикации</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="post in posts" :key="post.id" :style="{ backgroundColor: !post.is_published ? 'darkgrey' : '' }">
                        <td>{{ post.id }}</td>
                        <td>{{ post.user.name }}</td>
                        <td>{{ post.category.title }}</td>
                        <td>
                            <Link :href="route('admin.posts.edit', post.id)">{{ post.title }}</Link>
                        </td>
                        <td>{{ post.published_at ? new Date(post.published_at).toLocaleDateString() + ' ' + new Date(post.published_at).toLocaleTimeString() : '' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="props.paginator.total > props.paginator.per_page" class="mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li v-for="(link, key) in props.paginator.links" :key="key" :class="['page-item', { active: link.active, disabled: !link.url }]">
                        <Link v-if="link.url" class="page-link" :href="link.url" v-html="link.label"></Link>
                        <span v-else class="page-link" v-html="link.label"></span>
                    </li>
                </ul>
            </nav>
        </div>
    </AdminLayout>
</template>
