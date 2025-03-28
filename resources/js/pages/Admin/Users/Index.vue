<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    users: Array, // Типизируйте массив пользователей
});
</script>

<template>
    <AdminLayout title="Управление Пользователями">
        <template #sidebar>
            <AdminSidebar />
        </template>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <h1>Список пользователей</h1>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ new Date(user.created_at).toLocaleDateString() }}</td>
                            <td>
                                <Link :href="route('admin.users.edit', user.id)" class="btn btn-sm btn-primary">Редактировать</Link>
                                <form :action="route('admin.users.destroy', user.id)" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" :value="$page.props.csrf_token">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                                        Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
