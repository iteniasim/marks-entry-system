<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { watch, ref } from 'vue'
import { TRichSelect, TButton, TModal, TInput } from '@variantjs/vue'
import axios from 'axios'

const pageTitle = 'Marks Entry'

const props = defineProps({
    exams: Object,
    grades: Object,
})

const selectedGradeId = ref()
const studentsOfGrade = ref([])
const subjectsOfGrade = ref([])

const modalDetails = ref({
    show: false,
    title: null,
})

const markForm = useForm({
    student_id: null,
    exam_id: null,
    grade_id: null,
    subject_ids: [],
    obtained_marks: {},
})

const fetchGradeData = () => axios.get(route('gradeData', selectedGradeId.value)).then(res => {
    studentsOfGrade.value = res.data.students
    subjectsOfGrade.value = res.data.subjects
    markForm.grade_id = selectedGradeId.value
    markForm.subject_ids = subjectsOfGrade.value.map(subject => subject.id)
    markForm.obtained_marks = {}
    markForm.subject_ids.forEach(subjectId => {
        markForm.obtained_marks[subjectId] = 0
    })
})

watch(selectedGradeId, () => {
    studentsOfGrade.value = []
    fetchGradeData()
})

const openMarksEntryModal = (studentId, examId) => {
    let selectedStudent = studentsOfGrade.value.find(student => student.id === studentId)
    let selectedExam = props.exams.find(exam => exam.id === examId)

    if (selectedStudent.marks.filter(mark => mark.exam_id === examId && mark.grade_id === markForm.grade_id).length) {
        let marksForSelectedExam = selectedStudent.marks.filter(mark => mark.exam_id === examId && mark.grade_id === markForm.grade_id)
        markForm.subject_ids.forEach(subjectId => {
            markForm.obtained_marks[subjectId] = marksForSelectedExam.find(mark => mark.subject_id === subjectId).obtained_marks
        })
    }

    markForm.student_id = selectedStudent.id
    markForm.exam_id = selectedExam.id
    modalDetails.value.show = true
    modalDetails.value.title = `${selectedStudent.name} (Student) marks for ${selectedExam.name} (Exam)`
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
            fetchGradeData()
        },
    })
}
</script>

<template>
    <Head :title="pageTitle" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ pageTitle }}</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm overflow-none sm:rounded-lg">
                    <div class="px-6 py-5">
                        <div class="flex flex-col gap-4">
                            <!-- Select Grade -->
                            <div>
                                <label class="label">
                                    <span class="label-text">Grades</span>
                                </label>
                                <div>
                                    <t-rich-select
                                        :options="props.grades"
                                        v-model="selectedGradeId"
                                        name="grade_id"
                                        placeholder="Select Grade"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                            </div>
                            <!-- Student List -->
                            <div>
                                <div v-if="studentsOfGrade.length">
                                    <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                                        <thead class>
                                            <tr class>
                                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Student Name</th>
                                                <th v-for="(exam, index) in props.exams" :key="`exam-${index}`" class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                                    {{ exam.name }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            <tr
                                                v-for="(student, studentIndex) in studentsOfGrade"
                                                :key="`student-${studentIndex}`"
                                            >
                                                <td class="px-3 py-2 whitespace-no-wrap">
                                                    {{ student.name }}
                                                </td>
                                                <td v-for="(exam, examModalIndex) in props.exams" :key="`exam-marks-modal-${examModalIndex}`" class="px-3 py-2 whitespace-no-wrap">
                                                    <t-button :variant="student.marks.filter(mark => mark.exam_id === exam.id && mark.grade_id === markForm.grade_id).length ? 'success' : ''" @click="openMarksEntryModal(student.id, exam.id)">
                                                        Enter Marks
                                                    </t-button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else>
                                    No students in selected grade.
                                </div>
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
                    <template
                        v-for="(subject, subjectIndex) in subjectsOfGrade"
                        :key="`subject-${subjectIndex}-student-${markForm.student_id}`"
                    >
                        <tr>
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
                        <tr class="text-red-400" v-if="markForm.errors && markForm.errors[`obtained_marks.${subject.id}`]">
                            <td colspan="3">{{ markForm.errors[`obtained_marks.${subject.id}`] }}</td>
                        </tr>
                    </template>
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
