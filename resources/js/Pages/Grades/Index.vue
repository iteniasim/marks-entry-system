<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

const pageTitle = 'Grades'

const props = defineProps({
    grades: Object,
})
</script>

<template>
    <Head :title="pageTitle" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ pageTitle }}</h2>
        </template>

        <template #actions>
            <Link :href="route('grades.create')" class="btn btn-primary btn-sm">
                Add grade
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <table class="table min-w-full table-zebra">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="hover"
                            v-for="(grade, index) in props.grades"
                            :key="`grade-${index}`"
                        >
                            <td>{{ grade.name }}</td>
                            <td>
                                <Link class="btn btn-link" :href="route('grades.edit', grade.id)">
                                    Edit
                                </Link>
                                <Link class="btn btn-link" method="delete" as="button" type="button" :href="route('grades.destroy', grade.id)">
                                    Delete
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
