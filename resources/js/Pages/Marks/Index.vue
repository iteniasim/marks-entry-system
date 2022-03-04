<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

const pageTitle = 'Marks'

const props = defineProps({
    marks: Object,
})
</script>

<template>
    <Head :title="pageTitle" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ pageTitle }}</h2>
        </template>

        <template #actions>
            <Link :href="route('marks.create')" class="btn btn-primary btn-sm">
                Add Marks
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <table class="table min-w-full table-zebra">
                    <thead>
                        <tr>
                            <th>First</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="hover"
                            v-for="(mark, index) in props.marks"
                            :key="`mark-${index}`"
                        >
                            <td>{{ mark.first_name }}</td>
                            <td>
                                <Link class="btn btn-link" :href="route('marks.edit', mark.id)">
                                    Edit
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
