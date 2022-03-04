<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

const pageTitle = 'Students'

const props = defineProps({
    students: Object,
})
</script>

<template>
    <Head :title="pageTitle" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ pageTitle }}</h2>
        </template>

        <template #actions>
            <Link :href="route('students.create')" class="btn btn-primary btn-sm">
                Add Student
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <table class="table min-w-full table-zebra">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Roll</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="hover"
                            v-for="(student, index) in props.students"
                            :key="`student-${index}`"
                        >
                            <td>{{ student.name }}</td>
                            <td>{{ student.roll_no }}</td>
                            <td>{{ student.grade.name }}</td>
                            <td>
                                <Link class="btn btn-link" :href="route('students.edit', student.id)">
                                    Edit
                                </Link>
                                <Link class="btn btn-link" method="delete" as="button" type="button" :href="route('students.destroy', student.id)">
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
