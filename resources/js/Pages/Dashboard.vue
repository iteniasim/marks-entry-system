<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TInput, TButton, TRichSelect } from '@variantjs/vue'
import PaginationComponent from '@/Components/PaginationComponent.vue'
import axios from 'axios'
import { ref } from 'vue'

const props = defineProps({
    grades: Object,
})

const studentSearch = ref({
    search: null,
    grade: null,
})

const printResult = ref({
    name: null,
    grade_id: null,
})
const studentSummary = ref({
    name: null,
    grade_id: null,
})

const studentList = ref(null)

const updatePageData = (data) => {
    studentList.value = data.students
}

const searchStudent = () => {
    axios.get(route('students.index', { search: studentSearch.value.search, grade: studentSearch.value.grade })).then((res) => {
        studentList.value = res.data.students
    })
}

const searchStudentResults = () => {
    // axios.get(route('students.index', { search: studentSearch.value.search, grade: studentSearch.value.grade })).then((res) => {
    //     console.log(res.data)
    // })
}

const printSummary = () => {
    // axios.get(route('students.index', { search: studentSearch.value.search, grade: studentSearch.value.grade })).then((res) => {
    //     console.log(res.data)
    // })
}

</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid w-full h-screen grid-cols-3 p-4 overflow-auto divide-x-4">
                            <div class="pr-4">
                                <div class="p-2 my-4 border">
                                    <div class="text-lg font-semibold">
                                        Student Search
                                    </div>
                                    <form @submit.prevent="searchStudent" method="get">
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Name</span>
                                            </label>
                                            <TInput
                                                name="search"
                                                v-model="studentSearch.search"
                                                placeholder="search"
                                            />
                                        </div>
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Class</span>
                                            </label>
                                            <t-rich-select
                                                :options="props.grades"
                                                v-model="studentSearch.grade"
                                                name="grade"
                                                placeholder="Select Grade"
                                                value-attribute="id"
                                                text-attribute="name"
                                            />
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <t-button class="mt-8">
                                                Search
                                            </t-button>
                                        </div>
                                    </form>
                                </div>

                                <div class="p-2 my-4 border">
                                    <div class="text-lg font-semibold">
                                        Print Results
                                    </div>
                                    <form @submit.prevent="searchStudentResults" method="get">
                                        <div class="flex justify-around gap-2">
                                            <div class="items-center justify-center">
                                                <label class="label">
                                                    <span class="label-text">Class</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.grades"
                                                    v-model="printResult.grade_id"
                                                    name="grade_id"
                                                    placeholder="Select Grade"
                                                    value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                            <div>
                                                <label class="label">
                                                    <span class="label-text">Term</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.grades"
                                                    v-model="printResult.exam_id"
                                                    name="exam_id"
                                                    placeholder="Select Grade"
                                                    value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                        </div>
                                        <div class="px-4">
                                            <label class="label">
                                                <span class="label-text">Year</span>
                                            </label>
                                            <t-rich-select
                                                :options="props.grades"
                                                v-model="printResult.year"
                                                name="year"
                                                placeholder="Select Grade"
                                                value-attribute="id"
                                                text-attribute="name"
                                            />
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <t-button class="mt-8">
                                                Print
                                            </t-button>
                                        </div>
                                    </form>
                                </div>

                                <div class="p-2 my-4 border">
                                    <div class="text-lg font-semibold">
                                        Print Summary
                                    </div>
                                    <form @submit.prevent="printSummary" method="get">
                                        <div class="flex justify-around gap-2">
                                            <div class="items-center justify-center">
                                                <label class="label">
                                                    <span class="label-text">Class</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.grades"
                                                    v-model="studentSummary.grade_id"
                                                    name="grade_id"
                                                    placeholder="Select Grade"
                                                    value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                            <div>
                                                <label class="label">
                                                    <span class="label-text">Term</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.grades"
                                                    v-model="studentSummary.exam_id"
                                                    name="exam_id"
                                                    placeholder="Select Grade"
                                                    value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                        </div>
                                        <div class="px-4">
                                            <label class="label">
                                                <span class="label-text">Year</span>
                                            </label>
                                            <t-rich-select
                                                :options="props.grades"
                                                v-model="studentSummary.year"
                                                name="year"
                                                placeholder="Select Grade"
                                                value-attribute="id"
                                                text-attribute="name"
                                            />
                                        </div>
                                        <div class="flex items-center justify-end">
                                            <t-button class="mt-8">
                                                Print
                                            </t-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-span-2 pl-4">
                                <!-- Student List -->
                                <div v-if="studentList">
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
                                            <template v-if="studentList.total">
                                                <tr
                                                    v-for="(student, index) in studentList.data"
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
                                            </template>
                                            <tr v-else>
                                                <td class="px-3 py-2 whitespace-no-wrap" colspan="4">
                                                    No Students Available
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <pagination-component :is-ajax="true" :data="studentList" @update:page-data="updatePageData" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
