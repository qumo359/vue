<script setup>
import { defineProps } from 'vue';
import type { BlogPost as BlogPostType } from '@/types/BlogPost';

const props = defineProps({
    posts: {
        type: Object as () => {
            data: BlogPostType[];
        },
        required: true,
    },

});

console.log(props.posts); // Вы можете увидеть структуру объекта пагинатора
</script>

<template>
    <div>
        <h1>Список постов</h1>
        <ul v-for="post in props.posts.data" :key="post.id">
            <li>{{ post.title }}</li>
        </ul>

        <div v-if="props.posts.links && props.posts.links.length > 3">
            <div v-for="link in props.posts.links" :key="link.label" v-if="link.label !== '&laquo; Previous'" v-if="link.label !== '&raquo; Next'">
                <Link :href="link.url" :class="{ active: link.active }">{{ link.label }}</Link>
            </div>
            <Link v-if="props.posts.prev" :href="props.posts.prev">Previous</Link>
            <Link v-if="props.posts.next" :href="props.posts.next">Next</Link>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
};
</script>
