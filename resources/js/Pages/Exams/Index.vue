<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

const pageTitle = 'Exams'

const props = defineProps({
    exams: Object,
})
</script>

<template>
    <Head :title="pageTitle" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ pageTitle }}</h2>
        </template>

        <template #actions>
            <Link :href="route('exams.create')" class="btn btn-primary btn-sm">
                Add exams
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
                            v-for="(exam, index) in props.exams"
                            :key="`exam-${index}`"
                        >
                            <td>{{ exam.name }}</td>
                            <td>
                                <Link class="btn btn-link" :href="route('exams.edit', exam.id)">
                                    Edit
                                </Link>
                                <Link class="btn btn-link" method="delete" as="button" type="button" :href="route('exams.destroy', exam.id)">
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
