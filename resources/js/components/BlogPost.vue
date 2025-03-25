<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import type { BlogPost } from '@/types/BlogPost';

const route = useRoute();
const postId = ref(route.params.id);
const post = ref<BlogPost>({}as BlogPost);

const capitalizeFirstLetter = (string) => {
    if (!string) return '';
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
};

onMounted(() => {
    fetchPost();
});
</script>

<template>
    <div class="single-post">
        <div class="feature-img">
            <img v-if="post.post_image" :src="'/storage/test/' + post.post_image" class="img-fluid" alt="post image" />
            <img v-else class="img-fluid" src="/storage/test/imagenotfound.png" alt="image not found" />
        </div>
        <div class="blog_details">
            <h1>
                {{ post.title }}
            </h1>
            <ul class="blog-info-link mb-4 mt-3">
                <li>
                    <router-link :to="'/categories/' + post.category.slug" v-if="post.category">
                        <i class="fa fa-user"></i>{{ capitalizeFirstLetter(post.category.title) }}
                    </router-link>
                    <span v-else><i class="fa fa-user"></i> No Category </span>
                </li>
                <li>
                    <a href="#"><i class="fa fa-comments"></i>{{ post.comments_count }} Комментариев</a>
                </li>
            </ul>
            <p class="excerpt">
                {{ post.excerpt }}
            </p>
            <div class="quote-wrapper">
                <div class="quotes">
                    {{ post.excerpt }}
                </div>
            </div>
            <p v-html="post.content_html"></p>
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
