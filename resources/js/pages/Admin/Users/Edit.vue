<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';
// import { useToast } from 'vue3-toastify';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
});

// const toast = useToast();
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
        <div class="flex flex-col space-y-6 justify-content-center">
            <header>
                <h3 class="mb-0.5 text-base font-medium text-center p-3">Редактирование пользователя: {{ props.user?.name }}</h3>
<!--                <p class="text-sm text-muted-foreground">Update your name and email address</p>-->
            </header>
            <form class="space-y-6 flex flex-col items-center" >
                <div class="grid gap-2">
                    <label for="name" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Имя</label
                    ><input
                        class="mt-1 block h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        id="name"
                        required=""
                        autocomplete="name"
                        v-model="form.name"
                    />
                    <div class="mt-2" style="display: none"><p class="text-sm text-red-600 dark:text-red-500"></p></div>
                </div>
                <div class="grid gap-2">
                    <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        >Эмейл адрес</label
                    ><input
                        class="mt-1 block h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        id="email"
                        type="email"
                        required=""
                        autocomplete="username"
                        placeholder=this.user?.email
                        v-model="form.email"
                    />
                    <div class="mt-2" style="display: none"><p class="text-sm text-red-600 dark:text-red-500"></p></div>
                </div>
                <!--v-if-->
                <div class="flex items-center gap-4">
                    <button
                        class="[&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow ring-offset-background transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                    >
                        Сохранить
                    </button>
                    <button
                        class="[&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 inline-flex h-9 items-center justify-center gap-2 whitespace-nowrap rounded-md bg-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow ring-offset-background transition-colors hover:bg-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                    >
                        Отмена
                    </button>
                    <p class="text-sm text-neutral-600" style="display: none">Сохранено.</p>
                </div>
            </form>
        </div>
<!--        <div class="container">-->
<!--            <div class="row justify-content-center">-->
<!--                <div class="col-md-9 text-center">-->
<!--                    <h1>Редактирование пользователя: {{ props.user?.name }}</h1>-->
<!--                    <form @submit.prevent="submit">-->
<!--                        <div class="form-group">-->
<!--                            <label for="name">Имя:</label>-->
<!--                            <input v-model="form.name" type="text" class="form-control" id="name" required />-->
<!--                        </div>-->
<!--                        <div class="form-group">-->
<!--                            <label for="email">Email:</label>-->
<!--                            <input v-model="form.email" type="email" class="form-control" id="email" required />-->
<!--                        </div>-->
<!--                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>-->
<!--                        <Link :href="route('admin.users.index')" class="btn btn-secondary">Отмена</Link>-->
<!--                    </form>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </AdminLayout>
</template>

<style>
input {
    background-color: #1b1e21;
}
</style>
