<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const currentRoute = computed(() => route().current());
</script>

<template>
    <div class="card">
        <div class="card-body">
            <h5>Админ Меню</h5>
            <ul class="list-group">
                <li class="list-group-item">
                    <Link
                        :href="route('admin.posts.index')"
                        :class="{ active: currentRoute === 'admin.posts.index' }"
                    >
                        Посты
                    </Link>
                </li>
                <li class="list-group-item">
                    <Link
                        :href="route('admin.categories.index')"
                        :class="{ active: currentRoute === 'admin.categories.index' }"
                    >
                        Категории
                    </Link>
                </li>
                <li class="list-group-item">
                    <Link
                        :href="route('admin.users.index')"
                        :class="{ active: currentRoute === 'admin.users.index' }"
                    >
                        Пользователи
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>
.list-group-item a {
    text-decoration: none;
    color: inherit;
}

.list-group-item.active a {
    font-weight: bold; /* Выделяем активный пункт меню */
}
</style>
