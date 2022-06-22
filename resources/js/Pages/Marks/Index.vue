<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TButton, TInput, TRichSelect } from '@variantjs/vue'
import PaginationComponent from '@/Components/PaginationComponent.vue'
import axios from 'axios'
import { onMounted, ref, watch } from 'vue'
import debounce from 'lodash/debounce'

const pageTitle = 'Marks'

const props = defineProps({
    students: Object,
})

const studentList = ref([])

const grades = ref([])

const fectchGrades = () => {
    axios.get(route('grades.index', { withoutPagination: true })).then(res => {
        grades.value = res.data.grades
    })
}

onMounted(() => {
    fectchGrades()
    studentList.value = props.students
})

const search = ref(null)
const searchGrade = ref(null)

const fetchSearchResults = () => {
    axios.get(route('marks.index', { search: search.value, grade: searchGrade.value })).then(res => {
        studentList.value = res.data.students
        search.value = res.data.search ? res.data.search : null
        searchGrade.value = res.data.grade ? parseInt(res.data.grade) : null
    })
}

watch(search, debounce(fetchSearchResults, 2000))
watch(searchGrade, debounce(fetchSearchResults, 2000))
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
                <div v-if="studentList.total">
                    <div class="grid grid-cols-2 gap-12 mb-8">
                        <div>
                            <TInput
                                name="search"
                                v-model="search"
                                placeholder="Search Subject"
                            />
                        </div>
                        <div>
                            <t-rich-select
                                :options="grades"
                                v-model="searchGrade"
                                placeholder="Select Grade"
                                value-attribute="id"
                                text-attribute="name"
                            />
                        </div>
                    </div>
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                        <thead class>
                            <tr class>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Student</th>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Current Grade</th>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr
                                v-for="(student, index) in studentList.data"
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
                    <pagination-component :data="studentList" />
                </div>

                <div v-else>
                    No marks saved
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
