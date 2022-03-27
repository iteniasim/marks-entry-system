<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { computed, onMounted, ref } from 'vue'
import { TButton, TInput, TModal } from '@variantjs/vue'
var uniqWith = require('lodash/uniqWith')
var uniqBy = require('lodash/uniqBy')

const pageTitle = props.student.name + ' Marks'

const props = defineProps({
    student: Object,
})

const studentData = ref()
const relatedGrades = ref()
const relatedExams = ref()
const relatedSubjects = ref()

onMounted(() => {
    studentData.value = props.student
    relatedGrades.value = uniqBy(props.student.marks.map(mark => mark.grade), 'id')
    relatedExams.value = uniqBy(props.student.marks.map(mark => mark.exam), 'id')
    relatedSubjects.value = uniqBy(props.student.marks.map(mark => mark.subject), 'id')

    markForm.student_id = props.student.id
    markForm.subject_ids = relatedSubjects.value.map(subject => subject.id)
    markForm.obtained_marks = {}
    markForm.subject_ids.forEach(subjectId => {
        markForm.obtained_marks[subjectId] = 0
    })
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

const markForm = useForm({
    student_id: null,
    exam_id: null,
    grade_id: null,
    subject_ids: [],
    obtained_marks: {},
})

const modalDetails = ref({
    show: false,
    title: null,
})

const openMarksEntryModal = (examId, gradeId) => {
    if (props.student.marks.filter(mark => mark.exam_id === examId && mark.grade_id === gradeId).length) {
        let marksForSelectedExam = props.student.marks.filter(mark => mark.exam_id === examId && mark.grade_id === gradeId)
        markForm.subject_ids.forEach(subjectId => {
            markForm.obtained_marks[subjectId] = marksForSelectedExam.find(mark => mark.subject_id === subjectId).obtained_marks
        })
    }

    markForm.exam_id = examId
    markForm.grade_id = gradeId
    modalDetails.value.show = true
    modalDetails.value.title = `${props.student.name} (Student) marks for ${relatedExams.value.find(exam => exam.id === examId).name} (Exam) of ${relatedGrades.value.find(grade => grade.id === gradeId).name} (Grade)`
}

const closeMarksEntryModal = () => {
    modalDetails.value.show = false
    modalDetails.value.title = ''

    markForm.student_id = null
    markForm.exam_id = null
    markForm.obtained_marks = {}
    markForm.subject_ids.forEach(subjectId => {
        markForm.obtained_marks[subjectId] = 0
    })
}

const saveMarks = () => {
    markForm.post(route('marks.store'), {
        onSuccess: () => {
            closeMarksEntryModal()
        },
    })
}

const subjectsOfSelectedGrade = computed(() => relatedSubjects.value.filter(subject => subject.grade_id === markForm.grade_id))
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
                                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Actions</th>
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
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            <div class="flex gap-8">
                                                <Link
                                                    :href="route('student.grade.exam.marks', {
                                                        student:props.student.id,
                                                        grade:examGrade.grade.id,
                                                        exam:examGrade.exam.id
                                                    })"
                                                >
                                                    <t-button>
                                                        View
                                                    </t-button>
                                                </Link>
                                                <t-button
                                                    :variant="props.student.marks.filter(mark => mark.exam_id === examGrade.exam.id && mark.grade_id === examGrade.grade.id).length ? 'success' : ''"
                                                    @click="openMarksEntryModal(examGrade.exam.id, examGrade.grade.id)"
                                                >
                                                    Edit Marks
                                                </t-button>
                                            </div>
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

        <t-modal
            v-model="modalDetails.show"
            :header="modalDetails.title"
            @hidden="closeMarksEntryModal()"
        >
            <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                <thead class>
                    <tr class>
                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Subject Name</th>
                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Full Marks</th>
                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Pass Marks</th>
                        <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Obtained Marks</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr
                        v-for="(subject, index) in subjectsOfSelectedGrade"
                        :key="`subject-${index}-student-${markForm.student_id}`"
                    >
                        <td class="px-3 py-2 whitespace-no-wrap">
                            {{ subject.name }}
                        </td>
                        <td class="px-3 py-2 whitespace-no-wrap">
                            {{ subject.full_marks }}
                        </td>
                        <td class="px-3 py-2 whitespace-no-wrap">
                            {{ subject.pass_marks }}
                        </td>
                        <td class="px-3 py-2 whitespace-no-wrap">
                            <t-input v-model="markForm.obtained_marks[subject.id]" />
                        </td>
                    </tr>
                </tbody>
            </table>
            <template #footer>
                <div class="flex justify-between">
                    <t-button @click="closeMarksEntryModal" type="button">
                        Cancel
                    </t-button>
                    <t-button @click="saveMarks()" type="button" :disabled="markForm.processing">
                        Save
                    </t-button>
                </div>
            </template>
        </t-modal>
    </BreezeAuthenticatedLayout>
</template>
