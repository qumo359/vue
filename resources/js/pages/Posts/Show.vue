<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { computed } from 'vue';
import type BlogPost from '@/Models/BlogPost';
import type BlogCategory from '@/Models/BlogCategory';
import type Comment from '@/Models/BlogCategory';
import LikeButton from '@/components/LikeButton.vue';

interface BlogPostProps {
    post: {
        id: number;
        title: string;
        slug: string;
        post_image: string | null;
        comments: any[];
        excerpt: string;
        content_html: string;
        likes: any[];
        created_at: string;
    };
    comments: Comment[];
    categories: BlogCategory[];
    latestPosts: BlogPost[];
    prev: {
        id: number;
        title: string;
        slug: string;
        post_image: string | null;
    } | null;
    next: {
        id: number;
        title: string;
        slug: string;
        post_image: string | null;
    } | null;
    hasLikedPost: boolean;
    postLikesCount: number;
}

const props = defineProps<BlogPostProps>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin',
    },
    {
        title: 'Posts',
        href: '/posts',
    },
    {
        title: props.post.title,
    },
];

const user = computed(() => (route().has('auth.user') ? route().params.auth.user : null));
const isLoggedIn = computed(() => !!user.value);
//const hasLiked = computed(() => isLoggedIn.value && props.post.likes.some(like => like.user_id === user.value?.id));
const currentHasLikedPost = ref(props.hasLikedPost);
const currentPostLikesCount = ref(props.postLikesCount);

const commentForm = useForm({
    comment: '',
});

// const postCategory = ($slug) : BlogCategory | undefined => {
//     return props.categories.firstWhere('slug', $slug);
// };

const submitComment = () => {
    commentForm.post(route('blog.posts.comments.store', props.post), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
        },
    });
};
</script>

<template>
    <Head :title="props.post.title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <section class="blog_area single-post-area section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 posts-list">
                        <div class="single-post">
                            <div class="feature-img">
                                <img
                                    v-if="props.post.post_image"
                                    :src="`/storage/test/${props.post.post_image}`"
                                    class="img-fluid"
                                    alt="Post Image"
                                />
                                <img v-else src="https://prnt.sc/PwCGX68hkHLq" class="img-fluid" alt="Image Not Found" />
                            </div>
                            <div class="blog_details">
                                <h1>{{ props.post.title }}</h1>
                                <ul class="blog-info-link mb-4 mt-3">
                                    <li>
                                        <Link :href="route('categories.show', props.post.category.slug)">
                                            <i class="fa fa-user"></i>{{ $filters.ucfirst($filters.lower(props.post.category.title)) }}
                                        </Link>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-comments"></i>{{ props.comments.length }} Комментариев</a>
                                    </li>
                                </ul>
                                <p class="excerpt">{{ props.post.excerpt }}</p>
                                <div class="quote-wrapper">
                                    <div class="quotes">{{ props.post.excerpt }}</div>
                                </div>
                                <p v-html="props.post.content_html"></p>
                            </div>
                        </div>
                        <div class="navigation-top">
                            <div class="d-sm-flex justify-content-between text-center">
                                <p class="like-info">
                                    <span class="align-middle"><i class="fa fa-heart"></i></span>
                                    {{ currentLikesCount }} {{ $filters.trans_choice('Like|Likes', currentLikesCount) }}
                                </p>
                                <ul class="social-icons">
                                    <LikeButton
                                        :post-id="props.post.id"
                                        :initial-has-liked="props.hasLikedPost"
                                        :initial-likes-count="props.postLikesCount"
                                        @liked="hndleLiked"
                                        @unliked="handleUnliked"
                                        :has-liked-post = currentHasLikedPost
                                        :post-likes-count= currentPostLikesCount
                                    />
                                    <li>
                                        <a href="https://facebook.com"><i class="fa fa-facebook-f"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com"><i class="fa fa-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://dribble.com"><i class="fa fa-dribbble"></i></a>
                                    </li>
                                    <li>
                                        <a href="https://behance.net"><i class="fa fa-behance.net"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="navigation-area">
                            <div class="row">
                                <template v-if="props.prev">
                                    <div class="col-lg-6 col-md-6 col-12 nav-left d-flex justify-content-start align-items-center flex-row">
                                        <div class="thumb">
                                            <Link :href="route('blog.posts.show', props.prev.slug)">
                                                <img
                                                    v-if="props.prev.post_image"
                                                    :src="`/storage/test/${props.prev.post_image}`"
                                                    class="img-fluid"
                                                    alt=""
                                                />
                                                <img v-else src="/storage/test/imagenotfound.png" class="img-fluid" alt="" />
                                            </Link>
                                        </div>
                                        <div class="detials">
                                            <p>Предыдущий пост</p>
                                            <Link :href="route('blog.posts.show', props.prev.slug)">
                                                <h4>{{ props.prev.title }}</h4>
                                            </Link>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-lg-6 col-md-6 col-12 nav-left d-flex justify-content-start align-items-center flex-row">
                                        <div class="thumb"></div>
                                        <div class="detials">
                                            <p>Предыдущий пост</p>
                                            <a>
                                                <h4>Пусто</h4>
                                            </a>
                                        </div>
                                    </div>
                                </template>
                                <template v-if="props.next">
                                    <div class="col-lg-6 col-md-6 col-12 nav-left d-flex justify-content-start align-items-center flex-row">
                                        <div class="thumb">
                                            <Link :href="route('blog.posts.show', props.next.slug)">
                                                <img
                                                    v-if="props.next.post_image"
                                                    :src="`/storage/test/${props.next.post_image}`"
                                                    class="img-fluid"
                                                    alt=""
                                                />
                                                <img v-else src="/storage/test/imagenotfound.png" class="img-fluid" alt="" />
                                            </Link>
                                        </div>
                                        <div class="detials">
                                            <p>Следующий пост</p>
                                            <Link :href="route('blog.posts.show', props.next.slug)">
                                                <h4>{{ props.next.title }}</h4>
                                            </Link>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-lg-6 col-md-6 col-12 nav-left d-flex justify-content-start align-items-center flex-row">
                                        <h4>Пусто</h4>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="comments-area">
                            <h4>Комментарии</h4>
                            <div class="comment-list">
                                <template v-for="comment in props.comments" :key="comment.id">
                                    <div class="single-comment justify-content-between d-flex">
                                        <div class="user justify-content-between d-flex">
                                            <div class="thumb">
                                                <img src="/storage/test/commentimage.png" alt="Comment Image" />
                                            </div>
                                            <div class="desc">
                                                <p class="comment">{{ comment.body }}</p>
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <h5>
                                                            <a href="#">{{ comment.user.name }}</a>
                                                        </h5>
                                                        <p class="date">{{ $filters.diffForHumans(comment.created_at) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="comment-form">
                                <h4>Оставить комментарий</h4>
                                <form @submit.prevent="submitComment">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <textarea
                                                    v-model="commentForm.comment"
                                                    class="form-control w-100"
                                                    name="comment"
                                                    id="comment"
                                                    cols="30"
                                                    rows="9"
                                                    placeholder="Write Comment"
                                                >
Написать комментарий</textarea
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="button button-contactForm btn_1 boxed-btn">Отправить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div v-if="props.categories || props.latestPosts">
                            <div class="single-sidebar-widget post-category-widget">
                                <h4 class="widget-title">Категории</h4>
                                <ul class="cat-list">
                                    <li v-for="category in props.categories" :key="category.id">
                                        <Link :href="route('blog.categories.show', category.slug)" class="d-flex justify-content-between">
                                            <p>{{ $filters.ucfirst($filters.lower(category.title)) }}</p>
                                            <p>{{ category.posts_count }}</p>
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                            <div class="single-sidebar-widget popular-post-widget">
                                <h4 class="widget-title">Последние посты</h4>
                                <div class="thumb-wrap" v-for="latestPost in props.latestPosts" :key="latestPost.id">
                                    <div class="thumb">
                                        <Link :href="route('blog.posts.show', latestPost.slug)">
                                            <img
                                                class="img-fluid"
                                                :src="
                                                    latestPost.post_image
                                                        ? `/storage/test/${latestPost.post_image}`
                                                        : '/storage/test/imagenotfound.png'
                                                "
                                                alt=""
                                            />
                                        </Link>
                                    </div>
                                    <div class="details">
                                        <Link :href="route('blog.posts.show', latestPost.slug)">
                                            <h6>{{ latestPost.title }}</h6>
                                        </Link>
                                        <p>{{ $filters.diffForHumans(latestPost.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else v-if="props.sidebar" v-html="props.sidebar"></div>
                        <div v-else>@include('partials._sidebar')</div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
