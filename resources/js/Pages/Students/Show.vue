<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { onMounted, ref, watch } from 'vue'
import { TInput, TRichSelect } from '@variantjs/vue'
var uniqWith = require('lodash/uniqBy')

const pageTitle = props.student.name + ' Marks'

const props = defineProps({
    student: Object,
})

const studentData = ref()
const markList = ref()
const gradeList = ref()
const examList = ref()


const searchSubject = ref(null)
const searchGrade = ref(null)
const searchExam = ref(null)

onMounted(() => {
    studentData.value = props.student
    markList.value = props.student.marks
    gradeList.value = uniqWith(props.student.marks.map((mark) => mark.grade), 'id')
    examList.value = uniqWith(props.student.marks.map((mark) => mark.exam), 'id')
    searchGrade.value = studentData.value.grade_id
})

watch([searchGrade, searchExam, searchSubject], function() {
    markList.value = props.student.marks
    markList.value = markList.value.filter(mark => searchGrade.value ? mark.grade_id === searchGrade.value : true)
    markList.value = markList.value.filter(mark => searchExam.value ? mark.exam_id === searchExam.value : true)
    markList.value = markList.value.filter(mark => searchSubject.value ? mark.subject.name.includes(searchSubject.value) : true)
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
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div v-if="studentData" class="flex flex-col gap-8 px-6 py-5">
                        <div class="flex flex-col gap-4">
                            <div>
                                Student Name : {{ studentData.name }}
                            </div>
                            <div>
                                Current Grade: {{ studentData.grade.name }}
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <div class="grid grid-cols-3 gap-12 mb-8">
                                <div>
                                    <t-rich-select
                                        :options="gradeList"
                                        v-model="searchGrade"
                                        placeholder="Select Grade"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                                <div>
                                    <t-rich-select
                                        :options="examList"
                                        v-model="searchExam"
                                        placeholder="Select Exam"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                                <div>
                                    <TInput
                                        name="searchSubject"
                                        v-model="searchSubject"
                                        placeholder="Search Subject"
                                    />
                                </div>
                            </div>

                            <table v-if="markList.length" class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                                <thead class>
                                    <tr class>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Grade</th>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Exam</th>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Subject</th>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Full Marks</th>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Pass Marks</th>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Obtained Marks</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr
                                        v-for="(mark, index) in markList"
                                        :key="`mark-${index}`"
                                    >
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.grade.name }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.exam.name }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.subject.name }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.full_marks }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.pass_marks }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.obtained_marks }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div v-else>
                                No marks saved
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
