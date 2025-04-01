<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import AdminSidebar from '@/components/AdminSidebar.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { useCkeditor } from '@/composables/useCKEditor';

const props = defineProps({
    item: Object,
    categoryList: Array,
    comments: Array,
});



const form = useForm({
    title: props.item?.title || '',
    content_raw: props.item?.content_raw || '',
    post_image: null,
    category_id: props.item?.category_id || null,
    slug: props.item?.slug || '',
    excerpt: props.item?.excerpt || '',
    is_published: !!props.item?.is_published,
});

const editor = ref(null);
// const { editorRef, data: content_raw } = useCkeditor(editor, form.content_raw);
 const successMessage = computed(() => usePage().props.flash?.success);
const errors = computed(() => usePage().props.errors);

onMounted(() => {
    // ClassicEditor
    //     .create(editor.value, {
    //         licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NDE2NTExOTksImp0aSI6IjczNzExYjYxLTVhZGItNGY1Zi04MWI2LTdlYzQyNTI0MzQ2YSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6ImM0Mjg2N2M5In0.8ZafITx_lpqbj-g-hniggoivYqqxmXIJq3Gh26SQVAJ3tEJR2lgSchdT76Mmpy7gghP_ngL2L9wv1-4fNheckQ',
    //         plugins: [Essentials, Bold, Italic, Font, Paragraph],
    //         toolbar: [
    //             'undo', 'redo', '|', 'bold', 'italic', '|',
    //             'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
    //         ],
    //     })
    //     .then(newEditor => {
    //         newEditor.setData(form.content_raw);
    //         newEditor.model.document.on('change:data', () => {
    //             form.content_raw = newEditor.getData();
    //         });
    //     })
    //     .catch(error => {
    //         console.error(error);
    //     });
    //
    // if (successMessage.value) {
    //     toast.success(successMessage.value);
    // }

    if (Object.keys(errors.value).length > 0) {
        for (const key in errors.value) {
            toast.error(errors.value[key]);
        }
    }
});

const submit = () => {
    if (props.item?.exists) {
        form.post(route('admin.blog.update', props.item.id), {
            _method: 'patch',
        });
    } else {
        form.post(route('admin.posts.store'));
    }
};

const destroy = () => {
    if (props.item?.exists && confirm('Вы уверены, что хотите удалить этот пост?')) {
        form.delete(route('admin.posts.destroy', props.item.id));
    }
};

const config = ref({
    theme: "dark",
    minHeight: 600,
    buttons: "bold,italic,underline,|,ul,ol,|,image,link",
});
</script>

<template>
    <AdminLayout :title="props.item?.exists ? 'Редактирование статьи' : 'Создание статьи'">
        <template #sidebar>
            <AdminSidebar />
        </template>
        <JoditEditor v-model="content" :config="config" />

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center" v-if="props.item?.exists">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            ID: {{ props.item.id }}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center" v-if="props.item?.exists">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Создано</label>
                                <input type="text" :value="props.item?.created_at" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="title">Изменено</label>
                                <input type="text" :value="props.item?.updated_at" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label for="title">Опубликовано</label>
                                <input type="text" :value="props.item?.published_at" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            {{ props.item?.is_published ? 'Опубликовано' : 'Черновик' }}
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#maindata" role="tab">Основные данные</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#adddata" role="tab">Доп данные</a>
                                </li>
                            </ul>
                            <br>
                            <div class="tab-content">
                                <div class="tab-pane active" id="maindata" role="tabpanel">
                                    <div class="form-group">
                                        <label for="title">Заголовок</label>
                                        <input v-model="form.title" id="title" type="text" class="form-control" minlength="3" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="content_raw">Статья</label>
                                        <div ref="editor"></div>
                                    </div>
                                    <div class="form-group pt-2">
                                        <label for="post_image">Изображение поста</label>
                                        <input type="file" name="post_image" id="post_image" class="form-control-file" @input="form.post_image = $event.target.files[0]">
                                        <div v-if="props.item?.post_image" class="mt-2">
                                            <img :src="'/storage/test/' + props.item.post_image" alt="Текущее изображение" style="max-width: 200px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="adddata" role="tabpanel">
                                    <div class="form-group">
                                        <label for="category_id">Категория</label>
                                        <select v-model="form.category_id" id="category_id" class="form-control" required>
                                            <option v-for="category in props.categoryList" :key="category.id" :value="category.id">
                                                {{ category.title }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">Идентификатор</label>
                                        <input v-model="form.slug" id="slug" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="excerpt">Выдержка</label>
                                        <textarea v-model="form.excerpt" id="excerpt" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="form-check">
                                        <input v-model="form.is_published" type="checkbox" class="form-check-input" value="1" id="is_published">
                                        <label class="form-check-label" for="is_published">Опубликовано</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form v-if="props.item?.exists" @submit.prevent="destroy">
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card card-block">
                        <div class="card-body ml-auto">
                            <button type="submit" class="btn btn-link text-danger">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row justify-content-center mt-4" v-if="props.comments && props.comments.length > 0">
            <h4>Комментарии</h4>
            <div class="comment-list">
                <div v-for="comment in props.comments" :key="comment.id">
                    <pre>{{ JSON.stringify(comment, null, 2) }}</pre>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<style>
.jodit_theme_summer {
    --jd-color-background-default: #417505;
    --jd-color-border: #474025;
    --jd-color-panel: #5fd3a2;
    --jd-color-icon: #8b572a;
}
</style>
