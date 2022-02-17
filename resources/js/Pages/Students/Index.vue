<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'

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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full">
                                        <thead class="bg-white border-b">
                                            <tr>
                                                <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                    First
                                                </th>
                                                <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                    Last
                                                </th>
                                                <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                    Roll
                                                </th>
                                                <th scope="col" class="px-6 py-4 text-sm font-medium text-left text-gray-900">
                                                    Grade
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(student, index) in props.students" :key="`student-${index}`" class="border-b" :class="index % 2 == 0 ? 'bg-gray-100' : 'bg-white'">
                                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                                    {{ student.first_name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                                    {{ student.last_name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                                    {{ student.roll_no }}
                                                </td>
                                                <td class="px-6 py-4 text-sm font-light text-gray-900 whitespace-nowrap">
                                                    {{ student.grade.name }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
