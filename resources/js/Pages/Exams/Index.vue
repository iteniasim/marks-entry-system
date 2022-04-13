<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { TButton, TInput } from '@variantjs/vue'
import axios from 'axios'
import { onMounted, onUpdated, ref, watch } from 'vue'
var debounce = require('lodash/debounce')

const pageTitle = 'Exams'

const props = defineProps({
    exams: Object,
})

const examList = ref([])

onMounted(() => {
    examList.value = props.exams
})

onUpdated(() => {
    examList.value = props.exams
})

const search = ref(null)

const fetchSearchResults = () => {
    axios.get(route('exams.index', { search: search.value })).then(res => {
        search.value = res.data.search ? res.data.search : null
        examList.value = res.data.exams
    })
}

watch(search, debounce(fetchSearchResults, 2000))
</script>

<template>
    <Head :title="pageTitle" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ pageTitle }}</h2>
        </template>

        <template #actions>
            <Link :href="route('exams.create')">
                <t-button>
                    Add Exams
                </t-button>
            </Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8">
                    <div>
                        <TInput
                            name="search"
                            v-model="search"
                            placeholder="Search Exam"
                        />
                    </div>
                </div>
                <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                    <thead class>
                        <tr class>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Name</th>
                            <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <template v-if="examList.length">
                            <tr
                                v-for="(exam, index) in examList"
                                :key="`exam-${index}`"
                            >
                                <td class="px-3 py-2 whitespace-no-wrap">
                                    {{ exam.name }} <span class="font-semibold">{{ exam.is_final ? '(Final Term)' : '' }}</span>
                                </td>
                                <td class="flex items-center gap-4">
                                    <Link class="btn btn-link" :href="route('exams.edit', exam.id)">
                                        <t-button>
                                            Edit
                                        </t-button>
                                    </Link>
                                    <Link class="btn btn-link" method="delete" as="button" type="button" :href="route('exams.destroy', exam.id)">
                                        <t-button>
                                            Delete
                                        </t-button>
                                    </Link>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td class="px-3 py-2 whitespace-no-wrap" colspan="2">
                                No Exams Available
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
