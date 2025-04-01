<script setup lang="ts">
import type { BlogPost } from '@/types/BlogPost';
import { Link, useForm } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import LikeButton from '@/components/LikeButton.vue';

interface Props {
    post: BlogPost;
    hasLikedPost: boolean;
    postLikesCount: number;
}

const props = defineProps<Props>();
const currentHasLikedPost = ref(props.hasLikedPost);
const currentPostLikesCount = ref(props.postLikesCount);

const commentForm = useForm({
    comment: '',
});

const capitalizeFirstLetter = (string: string | null | undefined) => {
    if (!string) return '';
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

const handleLiked = (newCount: number) => {
    currentPostLikesCount.value = newCount;
    console.log('Лайкнуто, новый счетчик:', currentPostLikesCount.value);
};

const handleUnliked = (newCount: number) => {
    currentPostLikesCount.value = newCount;
    console.log('Лайк убран, новый счетчик:', currentPostLikesCount.value);
};
</script>

<template>
    <div class="single-post">
        <div class="feature-img">
<!--            <img v-if="props.post.post_image" :src="'/storage/test/' + props.post.post_image" class="img-fluid" alt="post image" />-->
            <img v-if="props.post.post_image" :src="props.post.post_image" class="img-fluid" alt="post image" />
            <img v-else class="img-fluid" src="https://img001.prntscr.com/file/img001/wuZhi0R1TW-ZZ8xJ3V0NYA.png" alt="image not found" />
        </div>
        <div class="blog_details">
            <Link :href="route('posts.show', { id: props.post.id })">
                 <h1><b> {{ props.post.title }} </b></h1>
            </Link>
            <ul class="blog-category-link mb-4 mt-3">
                <li>
                    <Link :href="`/categories/${props.post.category?.slug}`" v-if="props.post.category_id">
                        <i class="fa fa-user"></i>{{props.post.category.title}}
                    </Link>
                    <span v-else><i class="fa fa-user"></i> Нет категории </span>
                </li>
            </ul>
            <ul class="blog-info-link mb-4 mt-3">
                <li>
                    <Link href="#"><i class="fa fa-comments"></i>{{ props.post.comments_count }} Комментариев</Link>
                </li>
                <li>
                    <LikeButton
                        :postId="props.post.id"
                        :hasLikedPost="props.hasLikedPost"
                        :postLikesCount="props.postLikesCount"
                        @liked="handleLiked"
                        @unliked="handleUnliked"
                        />
                 </li>
            </ul>
            <p class="excerpt">
                {{ props.post.excerpt }}
            </p>
            <div class="quote-wrapper">
                <div class="quotes">
                    {{ props.post.excerpt }}
                </div>
            </div>
            <p v-html="props.post.content_html"></p>
        </div>
    </div>
</template>

<style scoped>
.single-post {
    /* Стили для отдельного поста */
}

.feature-img {
    margin-bottom: 30px;
}

.img-fluid {
    display: block;
    max-width: 100%;
    height: auto;
}

.blog_details {
    padding: 25px 0;
}

.blog_details h1 {
    font-size: 28px;
    color: #f50000;
    margin-bottom: 20px;
}

.blog_details h1:hover {
    color: #df10ee;
    transition: font-size 0.3s;
    text-transform: capitalize;
}

.blog-info-link {
    list-style: none;
    padding: 0;
    margin: 0;
}

.blog-category-link {
    list-style: none;
    padding: 0;
    margin: 0;
}

.blog-category-link li {
    display: inline-block;
    margin-right: 15px;
    font-size: 14px;
    color: #1fefe8;
}

.blog-category-link li a {
    color: #00ffea;
    transition: color 0.3s;
    text-decoration: none;
}

.blog-category-link li a:hover {
    color: #6b6db7; /* Пример цвета при наведении */
}


.blog-info-link li {
    display: inline-block;
    margin-right: 15px;
    font-size: 14px;
    color: #1f79ef;
}

.blog-info-link li a {
    color: #edff00;
    transition: color 0.3s;
    text-decoration: none;
}

.blog-info-link li a:hover {
    color: #ea2b27; /* Пример цвета при наведении */
}

.blog-info-link li i {
    margin-right: 5px;
}

.excerpt {
    font-size: 16px;
    color: #777777;
    line-height: 1.8;
    margin-bottom: 25px;
}

.quote-wrapper {
    background-color: #575757;
    padding: 30px;
    margin-bottom: 30px;
    border-left: 5px solid #136596; /* Пример стиля для цитаты */
}

.quotes {
    font-style: italic;
    color: #9dc4ac;
    font-size: 16px;
    line-height: 1.8;
}

.blog_details p {
    font-size: 16px;
    color: #59a136;
    line-height: 1.8;
    margin-bottom: 20px;
}
</style>
