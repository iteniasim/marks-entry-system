<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TButton } from '@variantjs/vue'

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
            <Link :href="route('students.create')">
                <t-button>
                    Add Student
                </t-button>
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                    <thead class>
                        <tr class>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Name</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Roll</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Grade</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <tr
                            v-for="(student, index) in props.students"
                            :key="`student-${index}`"
                        >
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ student.name }}
                            </td>
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ student.roll_no }}
                            </td>
                            <td class="px-3 py-2 whitespace-no-wrap">
                                {{ student.grade.name }}
                            </td>
                            <td class="flex items-center gap-4">
                                <Link :href="route('students.edit', student.id)">
                                    <t-button>
                                        Edit
                                    </t-button>
                                </Link>
                                <Link method="delete" as="button" type="button" :href="route('students.destroy', student.id)">
                                    <t-button>
                                        Delete
                                    </t-button>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
