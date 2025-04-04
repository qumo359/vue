<script setup lang="ts" xmlns="http://www.w3.org/1999/html">
import AdminSidebar from '@/components/AdminSidebar.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Link, useForm } from '@inertiajs/vue3';
import { form } from 'jodit/types/modules/image-editor/templates/form';

const form = useForm({});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

const props = defineProps({
    users: Array, // Типизируйте массив пользователей
});

const destroy = (user) => {
    console.log('Функция destroy вызвана', user.id);

    if (confirm(`Вы уверены, что хотите удалить пользователя? "${user.email}"?`)) {
        form.delete(route('admin.users.destroy', user.id));
    }
};
</script>

<template>
    <AdminLayout title="Управление Пользователями" :breadcrumbs="breadcrumbs">
        <template #sidebar>
            <AdminSidebar />
        </template>
        <p class="p-3 text-center font-bold">Список пользователей</p>
        <div class="data-table-container">
            <div class="row justify-content-center">
                <div class="col-9">
                    <table class="data-table-container">
                        <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Дата регистрации</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" v-for="user in users" :key="user.id">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td class="font-mono">{{ user.email }}</td>
                                <td>{{ new Date(user.created_at).toLocaleDateString() }}</td>
                                <td>
                                    <div class="flex justify-center space-x-2">
                                        <Link
                                            class="active:bg-yellow-1000 inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-yellow-900 focus:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-700 focus:ring-offset-2"
                                            :href="route('admin.users.edit', user.id)"
                                            >Редактировать
                                        </Link>
<!--                                        <form :action="route('admin.users.destroy', user.id)" method="POST" style="display: inline-block">-->
<!--                                            <input type="hidden" name="_method" value="DELETE" />-->
<!--                                            <input type="hidden" name="_token" :value="$page.props.csrf_token" />-->
<!--                                        </form>-->
                                            <button
                                                class="inline-flex items-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-700 focus:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-900"
                                                @click="destroy(user)"
                                            >
                                                <!--                                                onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')"-->
                                                Удалить
                                            </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
.data-table-container {
    width: 100%;
    box-sizing: border-box;
}

thead {
    border-bottom: 2px solid #491217;
}

td {
    border: 1px solid #1b1e21;
}
</style>
