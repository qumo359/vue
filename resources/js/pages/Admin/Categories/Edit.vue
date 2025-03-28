<script setup lang="ts">
import AdminSidebar from '@/components/AdminSidebar.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Font from '@ckeditor/ckeditor5-font/src/font';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { useToast } from 'vue3-toastify';

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

const editor = ref(null);
const toast = useToast();
const successMessage = computed(() => usePage().props.flash?.success);
const errors = computed(() => usePage().props.errors);

onMounted(() => {
    ClassicEditor.create(editor.value, {
        licenseKey: '...', // Вставьте ваш licenseKey, если есть
        plugins: [Essentials, Bold, Italic, Font, Paragraph],
        toolbar: ['undo', 'redo', '|', 'bold', 'italic', '|', 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'],
    })
        .then((newEditor) => {
            newEditor.setData(form.description);
            newEditor.model.document.on('change:data', () => {
                form.description = newEditor.getData();
            });
        })
        .catch((error) => {
            console.error(error);
        });

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
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
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
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a href="#maindata" class="nav-link active" role="tab" data-bs-toggle="tab">Основные Данные</a>
                                    </li>
                                </ul>
                                <br />
                                <div class="tab-content">
                                    <div class="tab-pane active" id="maindata" role="tabpanel">
                                        <div class="form-group">
                                            <label for="title">Заголовок</label>
                                            <input v-model="form.title" id="title" type="text" class="form-control" minlength="3" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="slug">Идентификатор</label>
                                            <input v-model="form.slug" id="slug" type="text" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="parent_id">Родитель</label>
                                            <select v-model="form.parent_id" class="form-control" placeholder="Выберите Категорию">
                                                <option :value="null">-- Выберите категорию --</option>
                                                <option v-for="category in props.categoryList" :key="category.id" :value="category.id">
                                                    {{ category.title }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Описание</label>
                                            <div ref="editor"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
