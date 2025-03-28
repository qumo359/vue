<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faHeart as faSolidHeart, faHeart as faRegularHeart } from '@fortawesome/free-regular-svg-icons';

interface Props {
    postId: number;
    hasLikedPost: boolean;
    postLikesCount: number;
}

const props = defineProps<Props>();

const hasLiked = ref(props.hasLikedPost);
const likesCount = ref(props.postLikesCount);

const authUser = computed(() => usePage().props.auth?.user);
const isLoggedIn = computed(() => !!authUser.value);

const likeForm = useForm({});
const unlikeForm = useForm({});

const emit = defineEmits(['liked', 'unliked']);

const likePost = () => {
    if (isLoggedIn.value) {
        likeForm.post(route('posts.like', { blogPost: props.postId }), {
            onSuccess: (response) => {
                likesCount.value = response.likesCount;
                hasLiked.value = response.hasLiked;
                emit('liked', likesCount.value);
            }
        });
    } else {
        window.location.href = route('login');
    }
};

const unlikePost = () => {
    if (isLoggedIn.value) {
        unlikeForm.post(route('posts.unlike', { blogPost: props.postId }), {
            method: 'post',
            onSuccess: (response) => {
                likesCount.value = response.likesCount;
                hasLiked.value = response.hasLiked;
                emit('unliked', likesCount.value);
            //     hasLiked.value = false;
            //     likesCount.value--;

            },
        });
    } else {
        window.location.href = route('login');
    }
};
</script>

<template>
    <li>
        <template v-if="isLoggedIn">
            <form @submit.prevent="hasLiked ? unlikePost : likePost">
                <button type="submit" class="btn btn-sm">
                    <font-awesome-icon
                        :icon="hasLiked ? faSolidHeart : faRegularHeart"
                        class="mr-1"
                        :style="{ color: hasLiked ? 'red' : '' }"
                    />
                    {{ likesCount }}
                </button>
            </form>
        </template>
        <Link v-else :href="route('login')" class="btn btn-sm btn-outline-secondary">
            <font-awesome-icon :icon="faRegularHeart" class="mr-1" /> Войдите
        </Link>
    </li>
</template>
<style scoped>

</style>
