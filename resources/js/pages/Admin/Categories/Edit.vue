<script setup lang="ts">
import AdminSidebar from '@/components/AdminSidebar.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
// import { useCkeditor } from '@/composables/useCkeditor';
// import { useToast } from 'vue3-toastify';

const form1 = useForm({});

const props = defineProps({
    item: Object,
    categoryList: Array,
});

const form = useForm({
    title: props.item?.title || '',
    slug: props.item?.slug || '',
    parent_id: props.item?.parent_id || null,
    description: props.item?.description || '',
});
const config = ref({
    theme: 'dark',
    minHeight: 200,
    maxHeight: 600,
    width: 600,
    buttons: 'bold,italic,underline,|',
});

const destroy = (category) => {
    console.log('Функция destroy вызвана', category.id);

    if (confirm(`Вы уверены, что хотите удалить категорию? "${category.title}"?`)) {
        form.delete(route('admin.categories.destroy', category.id));
    }
};

const editor = ref(null);
// const { editorRef, data: description } = useCkeditor(editor, form.description);
// const toast = useToast();
// const successMessage = computed(() => usePage().props.flash?.success);
// const errors = computed(() => usePage().props.errors);

// onMounted(() => {
//     if (successMessage.value) {
//         toast.success(successMessage.value);
//     }
//
//     if (Object.keys(errors.value).length > 0) {
//         for (const key in errors.value) {
//             toast.error(errors.value[key]);
//         }
//     }
// });

const submit = () => {
    if (props.item?.exists) {
        form.patch(route('admin.categories.update', props.item.id));
    } else {
        form.post(route('admin.categories.store'));
    }
};
</script>

<template>
    <AdminLayout :title="props.item?.exists ? `Редактирование категории: ${props.item.title}` : 'Создание категории'">
        <template #sidebar>
            <AdminSidebar />
        </template>

        <div class="container">
            <form @submit.prevent="submit">
                <div class="row justify-content-center" v-if="props.item?.exists">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li>ID: {{ props.item.id }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row justify-content-center" v-if="props.item?.exists">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Создано</label>
                                    <input type="text" :value="props.item?.created_at" class="form-control" disabled />
                                </div>
                                <div class="form-group">
                                    <label for="title">Изменено</label>
                                    <input type="text" :value="props.item?.updated_at" class="form-control" disabled />
                                </div>
                                <div class="form-group">
                                    <label for="title">Удалено</label>
                                    <input type="text" :value="props.item?.deleted_at" class="form-control" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <br />
                                <div class="d-flex tab-content justify-content-center align-items-center text-center">
                                    <div class="form-group">
                                        <label for="title">Заголовок</label>
                                        <input v-model="form.title" id="title" type="text" class="form-control text-center" minlength="3" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">Идентификатор</label>
                                        <input v-model="form.slug" id="slug" type="text" class="form-control text-center" />
                                    </div>
                                    <div class="form-group">
                                        <label for="parent_id">Родитель</label>
                                        <select v-model="form.parent_id" class="form-control text-center" placeholder="Выберите Категорию">
                                            <option :value="null">-- Выберите категорию --</option>
                                            <option v-for="category in props.categoryList" :key="category.id" :value="category.id">
                                                {{ category.title }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group justify-center">
                                        <label for="description">Описание:</label>
                                        <JoditEditor v-model="form.description" :config="config" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="justify-content-center align-items-center container text-center">
                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-md border border-transparent bg-green-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-700 focus:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 active:bg-green-900"
                                    >
                                        Сохранить
                                    </button>
                                    <button v-if="props.item?.id" class="delete-button" @click="destroy(props.item)">Удалить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<style>
.form-group {
    width: 100%;
    margin-bottom: 15px;
}

.delete-button {
    background-color: #ff0000; /* Синий цвет для кнопки "Редактировать" */
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.2s ease;
    margin-right: 5px;
}

.card .card-body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

input {
    background-color: #1b1e21;
}

.jodit_theme_summer {
    --jd-color-background-default: #417505;
    --jd-color-border: #474025;
    --jd-color-panel: #5fd3a2;
    --jd-color-icon: #8b572a;
}

select {
    background-color: #1b1e21;
}
</style>
