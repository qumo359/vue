<script setup lang="ts">
import AdminSidebar from '@/components/AdminSidebar.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Категории',
        href: '/categories',
    },
];

const props = defineProps({
    paginator: Object, // Типизируйте объект пагинатора
});

const destroy = (category) => {
    console.log('Функция destroy вызвана', category.id);

    if (confirm(`Вы уверены, что хотите удалить категорию? "${category.title}"?`)) {
        form.delete(route('admin.categories.destroy', category.id));
    }
};
const form = useForm({});


const categories = computed(() => props.paginator.data);
</script>

<template>
    <AdminLayout title="Управление Категориями" :breadcrumbs="breadcrumbs">
        <template #sidebar>
            <AdminSidebar />
        </template>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-toggleable-md navbar-light bg-faded p-3">
                        <Link
                            class="inline-flex items-center rounded-md border border-transparent bg-blue-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-900"
                            :href="route('admin.categories.create')"
                            >Добавить категорию
                        </Link>
                        <!--                        <Link class="btn btn-primary" :href="route('admin.categories.create')">  Добавить</Link>-->
                    </nav>
                    <div>
                        <div class="card-body">
                            <table class="table-hover table text-center">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Категория №</th>
                                        <th>Родитель</th>
                                        <th>Управление</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="category in categories" :key="category.id">
                                        <td>{{ category.id }}</td>
                                        <td :style="{ color: [0, 1].includes(category.parent_id) ? 'grey' : '' }">
                                            {{ category.parent_id }}
                                        </td>
                                        <td>
                                            <Link :href="route('admin.categories.edit', category.id)">
                                                <u>{{ category.title }}</u>
                                            </Link>
                                        </td>
                                                <td><button class="delete-button" @click="destroy(category)">Удалить</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="props.paginator.total > props.paginator.per_page" class="row justify-content-center mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li
                                        v-for="(link, key) in props.paginator.links"
                                        :key="key"
                                        :class="['page-item', { active: link.active, disabled: !link.url }]"
                                    >
                                        <Link v-if="link.url" class="page-link" :href="link.url" v-html="link.label"></Link>
                                        <span v-else class="page-link" v-html="link.label"></span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
.delete-button {
    background-color: #ff0000;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
    margin-right: 5px;
}
</style>
