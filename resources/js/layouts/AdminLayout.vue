<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    title: {
        type: String,
        default: 'Админ Панель',
    },
});
</script>

<template>
    <AppLayout>
        <Head :title="props.title" />

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <slot name="sidebar"></slot>
                        </div>
                        <div class="col-md-8">
                            <slot></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
