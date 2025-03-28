<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';
import { useToast } from 'vue3-toastify';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
});

const toast = useToast();
const successMessage = computed(() => usePage().props.flash?.success);
const errors = computed(() => usePage().props.errors);

onMounted(() => {
    if (successMessage.value) {
        toast.success(successMessage.value);
    }

    if (Object.keys(errors.value).length > 0) {
        for (const key in errors.value) {
            toast.error(errors.value[key]);
        }
    }
});

const submit = () => {
    form.put(route('admin.users.update', props.user.id));
};
</script>

<template>
    <AdminLayout :title="`Редактирование пользователя: ${props.user?.name}`">
        <template #sidebar>
            <AdminSidebar />
        </template>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <h1>Редактирование пользователя: {{ props.user?.name }}</h1>
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="name">Имя:</label>
                            <input v-model="form.name" type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input v-model="form.email" type="email" class="form-control" id="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        <Link :href="route('admin.users.index')" class="btn btn-secondary">Отмена</Link>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
