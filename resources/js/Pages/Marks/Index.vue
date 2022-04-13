<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TButton } from '@variantjs/vue'
import PaginationComponent from '@/Components/PaginationComponent.vue'

const pageTitle = 'Marks'

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
            <Link :href="route('marks.create')" class="btn btn-primary btn-sm">
                <t-button>
                    Add Marks
                </t-button>
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="props.students.total">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                        <thead class>
                            <tr class>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Student</th>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Grade</th>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr
                                v-for="(student, index) in props.students.data"
                                :key="`student-${index}`"
                            >
                                <td class="px-3 py-2 whitespace-no-wrap">
                                    {{ student.name }}
                                </td>
                                <td class="px-3 py-2 whitespace-no-wrap">
                                    {{ student.grade.name }}
                                </td>
                                <td>
                                    <Link class="btn btn-link" :href="route('students.studentMarksheets', student.id)">
                                        <t-button>View</t-button>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination-component :data="props.students" />
                </div>

                <div v-else>
                    No marks saved
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
