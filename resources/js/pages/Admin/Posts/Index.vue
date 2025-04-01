<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
 import ResultMessages from '@/components/ResultMessages.vue';
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
        <ResultMessages />
        <div class="container p-3">
        <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
            <Link class="btn btn-primary" :href="route('admin.posts.create')">Написать</Link>
            <Link v-if="flash.deleted_id" class="btn btn-danger" :href="route('admin.posts.restore', flash.deleted_id)">Отменить удаление</Link>
        </nav>
        </div>
    </AdminLayout>

        <div class="data-table-container">
            <h2 class="table-title">Список пользователей</h2>
                <table class="data-table">
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
                        <td>
                            <button class="edit-button">Редактировать</button>
                            <button class="delete-button">Удалить</button>
                        </td>

                    </tr>
                    </tbody>
                </table>
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


</template>

<style scoped>
.data-table-container {
    background-color: #fff; /* Белый фон */
    color: #4a5568; /* Темно-серый текст */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}

.table-title {
    color: #718096; /* Серый заголовок */
    margin-bottom: 15px;
    font-size: 1.25rem;
    font-weight: 500;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0; /* Светлый разделитель строк */
}

.data-table th {
    background-color: #f7fafc; /* Очень светлый серый для заголовков */
    font-weight: 600;
    color: #2d3748;
}

.data-table tbody tr:nth-child(even) {
    background-color: #edf2f7; /* Ещё более светлый серый для четных строк */
}

.edit-button,
.delete-button {
    background-color: #3182ce; /* Синий цвет для кнопки "Редактировать" */
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
    background-color: #e53e3e; /* Красный цвет для кнопки "Удалить" */
}

.edit-button:hover {
    background-color: #2c5282;
}

.delete-button:hover {
    background-color: #c53030;
}
</style>
