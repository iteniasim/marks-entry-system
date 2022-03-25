<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { computed, onMounted, ref } from 'vue'
var uniqWith = require('lodash/uniqWith')

const pageTitle = props.student.name + ' Marks'

const props = defineProps({
    student: Object,
})

const studentData = ref()

onMounted(() => {
    studentData.value = props.student
})

const uniqueExamGradeInSavedMarks = computed(() => {
    let marks = uniqWith(
        studentData.value.marks.map(mark => {
            return {
                'exam': {
                    'id': mark.exam.id,
                    'name': mark.exam.name,
                },
                'grade': {
                    'id': mark.grade.id,
                    'name': mark.grade.name,
                },
            }
        }),
        (prev, next) => {
            return  prev.exam.id === next.exam.id && prev.grade.id === next.grade.id
        },
    )
    return marks
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
                            <table v-if="uniqueExamGradeInSavedMarks.length" class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                                <thead>
                                    <tr class>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Grade</th>
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Exam</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr
                                        v-for="(examGrade, index) in uniqueExamGradeInSavedMarks"
                                        :key="`markSheet-${index}`"
                                    >
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ examGrade.grade.name }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ examGrade.exam.name }}
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
