<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    paginator: Object, // Типизируйте объект пагинатора
});

const categories = computed(() => props.paginator.data);
</script>

<template>
    <AdminLayout title="Управление Категориями">
        <template #sidebar>
            <AdminSidebar />
        </template>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                        <Link class="btn btn-primary" :href="route('admin.categories.create')">Добавить</Link>
                    </nav>
                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Категория</th>
                                    <th>Родитель</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="category in categories" :key="category.id">
                                    <td>{{ category.id }}</td>
                                    <td>
                                        <Link :href="route('admin.categories.edit', category.id)">
                                            {{ category.title }}
                                        </Link>
                                    </td>
                                    <td :style="{ color: [0, 1].includes(category.parent_id) ? 'grey' : '' }">
                                        {{ category.parent_title }}
                                    </td>
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
                                    <li v-for="(link, key) in props.paginator.links" :key="key" :class="['page-item', { active: link.active, disabled: !link.url }]">
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
