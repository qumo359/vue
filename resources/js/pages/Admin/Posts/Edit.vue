<script setup lang="ts">
import AdminSidebar from '@/components/AdminSidebar.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { toast } from 'vue3-toastify';
import { route } from 'ziggy-js';

const props = defineProps({
    item: Object,
    categoryList: Array,
    comments: Array,
});

const page = usePage();
const activeTab = ref('maindata');

function setActiveTab(tabId: string) {
    activeTab.value = tabId;
}

const breadcrumbs = computed(() => {
    const postId = page.props.item?.id;
    return [
        {
            title: 'Posts',
            route: '/posts',
        },
        {
            title: 'Post Edit',
            href: `/posts/${postId}/edit`,
        },
    ];
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
const successMessage = computed(() => usePage().props.flash?.success);
const errors = computed(() => usePage().props.errors);

onMounted(() => {
    console.log(props.item);
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
    form.content_raw = content.value;
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

const content = ref(props.item?.content_raw || '');
const config = ref({
    theme: 'dark',
    minHeight: 450,
    maxHeight: 450,
    buttons: 'bold,italic,underline,|,ul,ol,|,image,link',
});
</script>

<template>
    <AdminLayout :title="props.item?.exists ? 'Редактирование статьи' : 'Создание статьи'">
        <template #sidebar>
            <AdminSidebar />
        </template>
        <div class="container" id="admin-panel-content">
            <div class="row justify-content-center">
                <form @submit.prevent="submit" enctype="multipart/form-data">
                    <div class="flex flex-wrap">
                        <div id="post_edit" class="order-1 w-full md:w-8/12" v-if="props.item">
                            <div class="rounded-md border border-gray-600 bg-gray-800 shadow-md">
                                <div class="p-4 text-center">
                                    <!--                                    <div class="mb-4 rounded-md bg-gray-700 p-3 text-white">-->
                                    <!--                                        {{ props.item?.is_published ? 'Опубликовано' : 'Черновик' }}-->
                                    <!--                                    </div>-->
                                    <ul class="nav nav-tabs flex border-b border-gray-600" role="tablist">
                                        <li class="nav-item flex-grow text-center" role="presentation">
                                            <a
                                                class="nav-link block w-full p-3 focus:outline-none"
                                                :class="{
                                                    'active bg-gray-600 text-white': activeTab === 'maindata',
                                                    'text-gray-400 hover:text-gray-300': activeTab !== 'maindata',
                                                }"
                                                @click.prevent="setActiveTab('maindata')"
                                                href="#"
                                                role="tab"
                                                aria-controls="maindata"
                                                :aria-selected="activeTab === 'maindata'"
                                            >
                                                Основные данные
                                            </a>
                                        </li>
                                        <li class="nav-item flex-grow text-center" role="presentation">
                                            <a
                                                class="nav-link block w-full p-3 focus:outline-none"
                                                :class="{
                                                    'active bg-gray-600 text-white': activeTab === 'adddata',
                                                    'text-gray-400 hover:text-gray-300': activeTab !== 'adddata',
                                                }"
                                                @click.prevent="setActiveTab('adddata')"
                                                href="#"
                                                role="tab"
                                                aria-controls="adddata"
                                                :aria-selected="activeTab === 'adddata'"
                                            >
                                                Доп. данные
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-4">
                                        <div
                                            class="tab-pane fade"
                                            :class="{ 'show active': activeTab === 'maindata' }"
                                            v-show="activeTab === 'maindata'"
                                            id="maindata"
                                            role="tabpanel"
                                            aria-labelledby="maindata-tab"
                                        >
                                            <div class="form-group mb-4 w-full">
                                                <label for="title" class="mb-2 block text-sm font-bold text-gray-400">Заголовок</label>
                                                <input
                                                    v-model="form.title"
                                                    id="title"
                                                    type="text"
                                                    class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                    minlength="3"
                                                    required
                                                />
                                            </div>
                                            <div class="form-group mb-4 w-full">
                                                <label for="content_raw" class="mb-2 block text-sm font-bold text-gray-400">Статья</label>
                                                <JoditEditor id="content_raw" v-model="content" :config="config" />
                                            </div>
                                            <div class="form-group mb-4 w-full pt-2">
                                                <label for="post_image" class="mb-2 block text-sm font-bold text-gray-400">Изображение поста</label>
                                                <label
                                                    class="flex w-full cursor-pointer flex-col items-center justify-center rounded-md border border-gray-600 bg-gray-700 px-4 py-2 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                                                >
                                                    <svg
                                                        class="h-8 w-8 text-gray-400 group-hover:text-white"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                                        ></path>
                                                    </svg>
                                                    <span class="mt-1 text-sm text-gray-400 group-hover:text-white">Выберите файл</span>
                                                    <input
                                                        type="file"
                                                        name="post_image"
                                                        id="post_image"
                                                        class="sr-only"
                                                        @input="form.post_image = $event.target.files[0]"
                                                    />
                                                </label>
                                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                                    {{ form.progress.percentage }}%
                                                </progress>
                                                <div v-if="props.item?.post_image" class="mt-2">
                                                    <img
                                                        :src="'/storage/test/' + props.item.post_image"
                                                        alt="Текущее изображение"
                                                        class="block max-w-[200px]"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="tab-pane fade"
                                            :class="{ 'show active': activeTab === 'adddata' }"
                                            v-show="activeTab === 'adddata'"
                                            id="adddata"
                                            role="tabpanel"
                                            aria-labelledby="adddata-tab"
                                        >
                                            <div class="form-group mb-4 w-full">
                                                <label for="category_id" class="mb-2 block text-sm font-bold text-gray-400">Категория</label>
                                                <select
                                                    v-model="form.category_id"
                                                    id="category_id"
                                                    class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                    required
                                                >
                                                    <option :value="null" disabled>Выберите категорию</option>
                                                    <option v-for="category in props.categoryList" :key="category.id" :value="category.id">
                                                        {{ category.title }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-4 w-full">
                                                <label for="slug" class="mb-2 block text-sm font-bold text-gray-400">Идентификатор</label>
                                                <input
                                                    v-model="form.slug"
                                                    id="slug"
                                                    type="text"
                                                    class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                />
                                            </div>
                                            <div class="form-group mb-4 w-full">
                                                <label for="excerpt" class="mb-2 block text-sm font-bold text-gray-400">Выдержка</label>
                                                <textarea
                                                    v-model="form.excerpt"
                                                    id="excerpt"
                                                    class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                    rows="3"
                                                ></textarea>
                                            </div>
                                            <div class="form-check mb-4 flex items-center">
                                                <label class="form-check-label ml-2 text-gray-300" for="is_published">Опубликовано</label>
                                                <div
                                                    class="relative left-1 mr-2 inline-block w-10 select-none align-middle transition duration-200 ease-in"
                                                >
                                                    <input
                                                        v-model="form.is_published"
                                                        type="checkbox"
                                                        id="is_published"
                                                        :true-value="1"
                                                        :false-value="0"
                                                        class="peer absolute left-0 top-0 h-full w-full cursor-pointer appearance-none rounded-full border-2 border-gray-300 transition-all duration-200 ease-in-out checked:border-transparent checked:bg-green-500 focus:outline-none focus:ring-0"
                                                    />
                                                    <label
                                                        for="is_published"
                                                        class="block h-4 w-10 cursor-pointer rounded-full bg-gray-700 peer-checked:bg-green-500"
                                                    ></label>
                                                    <span
                                                        class="absolute left-0.5 top-0.5 h-3 w-3 rounded-full bg-white shadow-sm transition-all duration-200 ease-in-out peer-checked:translate-x-6"
                                                    ></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="post_info" class="order-2 w-full pl-4 md:w-4/12" v-if="props.item">
                            <div class="">
                                <div class="rounded-md border border-gray-600 bg-gray-800 shadow-md">
                                    <div class="flex flex-col gap-y-4 p-4">
                                        <!--                                    <div class="p-4">ID: {{ props.item.id }}</div>-->
                                        <div class="form-group mb-4 w-full">
                                            <label for="post_id" class="mb-2 block text-sm font-bold text-gray-400">Id поста: </label>
                                            <input
                                                type="text"
                                                :value="props.item?.id"
                                                class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                disabled
                                            />
                                        </div>
                                        <div class="form-group mb-4 w-full">
                                            <label for="title" class="mb-2 block text-sm font-bold text-gray-400">Создано</label>
                                            <input
                                                type="text"
                                                :value="props.item?.created_at"
                                                class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                disabled
                                            />
                                        </div>
                                        <div class="form-group mb-4 w-full">
                                            <label for="title" class="mb-2 block text-sm font-bold text-gray-400">Изменено</label>
                                            <input
                                                type="text"
                                                :value="props.item?.updated_at"
                                                class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                disabled
                                            />
                                        </div>
                                        <div class="form-group mb-4 w-full">
                                            <label for="title" class="mb-2 block text-sm font-bold text-gray-400">Опубликовано</label>
                                            <input
                                                type="text"
                                                :value="props.item?.published_at"
                                                class="focus:shadow-outline w-full appearance-none rounded border bg-gray-700 px-3 py-2 leading-tight text-white shadow focus:outline-none"
                                                disabled
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row justify-content-center mt-3">
                    <div class="col-md-8">
                        <div class="rounded-md border border-gray-600 bg-gray-800 shadow-md">
                            <div class="flex justify-around p-4">
                                <button
                                    type="submit"
                                    class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none"
                                >
                                    Сохранить
                                </button>
                                <form v-if="props.item" @submit.prevent="destroy" class="ml-2">
                                    <button
                                        type="submit"
                                        class="focus:shadow-outline rounded bg-red-500 px-4 py-2 font-bold text-white hover:bg-red-700 focus:outline-none"
                                    >
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-4" v-if="props.comments && props.comments.length > 0">
                <h4 class="mb-2 text-lg font-semibold text-gray-400">Комментарии</h4>
                <div class="comment-list">
                    <div v-for="comment in props.comments" :key="comment.id" class="mb-2 rounded-md bg-gray-700 p-4">
                        <pre class="whitespace-pre-wrap text-white">{{ JSON.stringify(comment, null, 2) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
