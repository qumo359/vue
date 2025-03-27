<script setup lang="ts">
import type { BlogPost } from '@/types/BlogPost';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    post: {
        type: Object as () => BlogPost,
        required: true,
    },
});

const capitalizeFirstLetter = (string: string | null | undefined) => {
    if (!string) return '';
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};
</script>

<template>
    <div class="single-post">
        <div class="feature-img">
            <img v-if="props.post.post_image" :src="'/storage/test/' + props.post.post_image" class="img-fluid" alt="post image" />
            <img v-else class="img-fluid" src="/public/imagenotfound.png" alt="image not found" />
        </div>
        <div class="blog_details">
            <Link :href="route('posts.show', { id: props.post.id })">
                  <b> {{ props.post.title }} </b>
            </Link>
            <ul class="blog-info-link mb-4 mt-3">
                <li>
                    <Link :href="`/categories/${props.post.category?.slug}`" v-if="props.post.category">
                        <i class="fa fa-user"></i>{{ capitalizeFirstLetter(props.post.category.title) }}
                    </Link>
                    <span v-else><i class="fa fa-user"></i> Нет категории </span>
                </li>
                <li>
                    <Link href="#"><i class="fa fa-comments"></i>{{ props.post.comments_count }} Комментариев</Link>
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
    color: #222222;
    margin-bottom: 20px;
}

.blog-info-link {
    list-style: none;
    padding: 0;
    margin: 0;
}

.blog-info-link li {
    display: inline-block;
    margin-right: 15px;
    font-size: 14px;
    color: #999999;
}

.blog-info-link li a {
    color: #999999;
    transition: color 0.3s;
    text-decoration: none;
}

.blog-info-link li a:hover {
    color: #ff4a52; /* Пример цвета при наведении */
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
    background-color: #f9f9ff;
    padding: 30px;
    margin-bottom: 30px;
    border-left: 5px solid #ff4a52; /* Пример стиля для цитаты */
}

.quotes {
    font-style: italic;
    color: #555555;
    font-size: 16px;
    line-height: 1.8;
}

.blog_details p {
    font-size: 16px;
    color: #777777;
    line-height: 1.8;
    margin-bottom: 20px;
}
</style>
