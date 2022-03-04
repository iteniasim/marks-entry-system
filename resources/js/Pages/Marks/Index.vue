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
            <Link :href="route('marks.create')" class="btn btn-primary btn-sm">Add Marks</Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                    <thead class>
                        <tr class>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Student</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Subject</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Exam</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Grade</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Full Marks</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Pass Marks</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Obtained Marks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <tr
                            v-for="(mark, index) in props.marks"
                            :key="`mark-${index}`"
                        >
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ mark.student.name }}
                            </td>
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ mark.subject.name }}
                            </td>
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ mark.exam.name }}
                            </td>
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ mark.grade.name }}
                            </td>
                            <td>
                                <Link class="btn btn-link" :href="route('marks.edit', mark.id)">Edit</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
