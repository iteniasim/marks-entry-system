<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TButton, TInput, TRichSelect } from '@variantjs/vue'
import { onMounted, onUpdated, ref, watch } from 'vue'
import axios from 'axios'
import PaginationComponent from '@/Components/PaginationComponent.vue'
var debounce = require('lodash/debounce')
var keys = require('lodash/keys')

const pageTitle = 'Subjects'

const props = defineProps({
    subjects: Object,
    grades: Object,
})

const subjectList = ref([])

onMounted(() => {
    subjectList.value = props.subjects
})

onUpdated(() => {
    subjectList.value = props.subjects
})

const search = ref(null)
const searchGrade = ref(null)

const fetchSearchResults = () => {
    axios.get(route('subjects.index', { search: search.value, grade: searchGrade.value })).then(res => {
        search.value = res.data.search ? res.data.search : null
        searchGrade.value = res.data.grade ? parseInt(res.data.grade) : null
        subjectList.value = res.data.subjects
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
            <Link :href="route('subjects.create')">
                <t-button>
                    Add subjects
                </t-button>
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                            :options="props.grades"
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
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Name</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Grade</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Full Marks</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Pass Marks</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <template v-if="keys(subjectList.data).length">
                            <template
                                v-for="(subjectGroup, gradeId) in subjectList.data"
                                :key="`subject-${gradeId}`"
                            >
                                <tr>
                                    <td colspan="5" class="text-center">
                                        {{ props.grades.find(grade=>grade.id === parseInt(gradeId)).name }}
                                    </td>
                                </tr>
                                <tr
                                    v-for="(subject, index) in subjectGroup"
                                    :key="`subject-${index}`"
                                >
                                    <td class="px-3 py-2 whitespace-no-wrap">
                                        {{ subject.name }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-no-wrap">
                                        {{ subject.grade.name }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-no-wrap">
                                        {{ subject.full_marks }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-no-wrap">
                                        {{ subject.pass_marks }}
                                    </td>
                                    <td class="flex items-center gap-4">
                                        <Link class="btn btn-link" :href="route('subjects.edit', subject.id)">
                                            <t-button>Edit</t-button>
                                        </Link>
                                        <Link class="btn btn-link" method="delete" as="button" type="button" :href="route('subjects.destroy', subject.id)">
                                            <t-button>Delete</t-button>
                                        </Link>
                                    </td>
                                </tr>
                            </template>
                        </template>
                        <tr v-else>
                            <td class="px-3 py-2 whitespace-no-wrap" colspan="5">
                                No Subjects Available
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination-component :data="subjectList" />
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
