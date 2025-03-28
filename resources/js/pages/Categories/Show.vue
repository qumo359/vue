<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface BlogCategory {
    id: number;
    title: string;
    parent_id: number | null;
    parentTitle: string | null;
}

interface Paginator {
    data: BlogCategory[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    meta: {
        current_page: number;
        from: number | null;
        last_page: number;
        path: string;
        per_page: number;
        to: number | null;
        total: number;
    } | null;
    total: number;
    count: number;
}

interface Props {
    paginator: Paginator;
}

const props = defineProps<Props>();

const shouldGreyOutParent = (parentId: number | null): boolean => {
    return [0, 1].includes(parentId as number);
};
</script>

<template>
    <Head title="Категории" />

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Категория</th>
                                <th>Родитель</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in props.paginator.data" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>
                                    {{ item.title }}
                                </td>
                                <td :style="{ color: shouldGreyOutParent(item.parent_id) ? 'grey' : '' }">
                                    {{ item.parentTitle }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="props.paginator.meta?.total > props.paginator.meta?.per_page"
             class="row justify-content-center mt-3">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li v-for="(link, key) in props.paginator.links" :key="key"
                            :class="['page-item', { active: link.active, disabled: link.url === null }]">
                            <Link v-if="link.url" class="page-link" :href="link.url" v-html="link.label" />
                            <span v-else class="page-link" v-html="link.label" />
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
