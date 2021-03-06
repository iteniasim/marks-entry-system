<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TButton, TRichSelect, TModal, TToggle } from '@variantjs/vue'
import PaginationComponent from '@/Components/PaginationComponent.vue'
import axios from 'axios'
import { ref } from 'vue'
import MarkSheetLayout from '@/Components/MarkSheetLayout.vue'
import _ from 'lodash'
import vPrint from 'vue3-print-nb'
import MarksSummaryLayout from '../Components/MarksSummaryLayout.vue'

const props = defineProps({
    grades: Object,
    exams: Object,
})

const studentSearch = ref({
    search: null,
    grade: null,
})

const years = [
    2022,
    2023,
    2024,
    2025,
    2026,
    2027,
    2028,
    2029,
    2030,
]

const printResult = ref({
    grade_id: null,
    exam_id: null,
    year: null,
})

const printSummary = ref({
    grade_id: null,
    exam_id: null,
    year: null,
})

const markForm = useForm({
    student_id: null,
    exam_id: null,
    grade_id: null,
    subject_ids: [],
    obtained_marks: {},
    absent_days: 0,
    present_days: 0,
})

const modalDetails = ref({
    show: false,
    title: null,
})

const studentList = ref(null)

const markList = ref(null)

const otherMarkList = ref(null)

const gpaDetails = ref(null)

const subjectsOfGrade = ref([])

const gradeDisplay = ref(true)

const showSummary = ref(false)

const showMarkSheets = ref(false)

const openMarksEntryModal = (studentId, examId) => {
    let selectedStudent = studentList.value.data.find(student => student.id === studentId)
    let selectedExam = props.exams.find(exam => exam.id === examId)

    if (!subjectsOfGrade.value.filter(subject => subject.grade_id === selectedStudent.grade.id).length) {
        fetchSubjectsOfGrade(selectedStudent.grade.id).then(() => {
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
        })
    } else {
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
}

const fetchSubjectsOfGrade = (gradeId) => axios.get(route('gradeData', gradeId)).then(res => {
    subjectsOfGrade.value = res.data.subjects
    markForm.grade_id = gradeId
    markForm.subject_ids = subjectsOfGrade.value.map(subject => subject.id)
    markForm.obtained_marks = {}
    markForm.subject_ids.forEach(subjectId => {
        markForm.obtained_marks[subjectId] = 0
    })
})

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

const updatePageData = (data) => {
    studentList.value = data.students
}

const saveMarks = () => {
    markForm.post(route('marks.store'), {
        onSuccess: () => {
            closeMarksEntryModal()
        },
    })
}

const searchStudent = () => {
    axios.get(route('students.index', { search: studentSearch.value.search, grade: studentSearch.value.grade, withMarks: true })).then((res) => {
        markList.value = null
        otherMarkList.value = null
        gpaDetails.value = null

        studentList.value = res.data.students
        subjectsOfGrade.value = res.data.gradeSubjects
    })
}

const searchStudentResults = (year, examId, gradeId) => {
    return axios.get(route('year.exam.grade.marks', { year: year, exam: examId, grade: gradeId })).then((res) => {
        studentList.value = null

        markList.value = res.data.marks
        otherMarkList.value = res.data.otherExamMarks
        gpaDetails.value = res.data.gpaDetails
    })
}

const printAllResults = () => {
    searchStudentResults(printResult.value.year, printResult.value.exam_id, printResult.value.grade_id).then(() => {
        showMarkSheets.value = true
        showSummary.value = false
        document.getElementById('print-all-button').click()
    })
}

const printResultSummary = () => {
    searchStudentResults(printSummary.value.year, printSummary.value.exam_id, printSummary.value.grade_id).then(() => {
        showMarkSheets.value = false
        showSummary.value = true
        document.getElementById('print-summary-button').click()
    })
}
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid w-full h-screen grid-cols-4 p-4 overflow-auto divide-x-4">
                            <div class="pr-4">
                                <!-- student search box -->
                                <div class="p-2 my-4 border">
                                    <div class="text-lg font-semibold">
                                        Student Search
                                    </div>
                                    <form @submit.prevent="searchStudent" method="get">
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Name</span>
                                            </label>
                                            <TInput name="search" v-model="studentSearch.search" placeholder="Search" />
                                        </div>
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Class</span>
                                            </label>
                                            <t-rich-select
                                                :options="props.grades" v-model="studentSearch.grade"
                                                name="grade" placeholder="Select Class" value-attribute="id"
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

                                <!-- print result box -->
                                <div class="p-2 my-4 border">
                                    <div class="text-lg font-semibold">
                                        Print Results
                                    </div>
                                    <form class="grid grid-rows-5">
                                        <div>
                                            <div class="items-center justify-center">
                                                <label class="label">
                                                    <span class="label-text">Class</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.grades" v-model="printResult.grade_id"
                                                    name="grade_id" placeholder="Select Class" value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <label class="label">
                                                    <span class="label-text">Term</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.exams" v-model="printResult.exam_id"
                                                    name="exam_id" placeholder="Select Exam" value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Year</span>
                                            </label>
                                            <t-rich-select
                                                :options="years" v-model="printResult.year"
                                                name="year" placeholder="Select Year"
                                            />
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="ml-4">
                                                    {{ gradeDisplay ? "Show Grade" : "Show Marks" }}
                                                </div>
                                                <div class="">
                                                    <t-toggle v-model="gradeDisplay" />
                                                </div>
                                            </div>
                                            <t-button @click.prevent="printAllResults" class="mt-8">
                                                Print All
                                            </t-button>
                                        </div>
                                    </form>
                                </div>

                                <!-- print summary box -->
                                <div class="p-2 my-4 border">
                                    <div class="text-lg font-semibold">
                                        Print Summary
                                    </div>
                                    <form class="grid grid-rows-5">
                                        <div>
                                            <div class="items-center justify-center">
                                                <label class="label">
                                                    <span class="label-text">Class</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.grades" v-model="printSummary.grade_id"
                                                    name="grade_id" placeholder="Select Class" value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <label class="label">
                                                    <span class="label-text">Term</span>
                                                </label>
                                                <t-rich-select
                                                    :options="props.exams" v-model="printSummary.exam_id"
                                                    name="exam_id" placeholder="Select Exam" value-attribute="id"
                                                    text-attribute="name"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="label">
                                                <span class="label-text">Year</span>
                                            </label>
                                            <t-rich-select
                                                :options="years" v-model="printSummary.year"
                                                name="year" placeholder="Select Year"
                                            />
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="ml-4">
                                                    {{ gradeDisplay ? "Show Grade" : "Show Marks" }}
                                                </div>
                                                <div class="">
                                                    <t-toggle v-model="gradeDisplay" />
                                                </div>
                                            </div>
                                            <t-button @click.prevent="printResultSummary" class="mt-8">
                                                Print Summary
                                            </t-button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-span-3 pl-4">
                                <!-- Student List -->
                                <div v-if="studentList">
                                    <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                                        <thead class>
                                            <tr class>
                                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                                    Name
                                                </th>
                                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                                    Roll
                                                </th>
                                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                                    Grade
                                                </th>
                                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                                    Actions
                                                </th>
                                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                                    Marks
                                                    Entry
                                                </th>
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
                                                    <td class="px-3 py-2 whitespace-no-wrap">
                                                        <div class="flex items-center justify-between gap-4">
                                                            <Link :href="route('students.edit', student.id)">
                                                                <t-button>
                                                                    Edit
                                                                </t-button>
                                                            </Link>
                                                            <Link
                                                                method="delete" as="button" type="button"
                                                                :href="route('students.destroy', student.id)"
                                                            >
                                                                <t-button>
                                                                    Delete
                                                                </t-button>
                                                            </Link>
                                                        </div>
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-no-wrap">
                                                        <div class="flex items-center justify-between">
                                                            <div
                                                                class="text-blue-600 underline cursor-pointer"
                                                                v-for="exam in props.exams"
                                                                :key="`marks-student-${student.id}-exam-${exam.id}`"
                                                                @click="openMarksEntryModal(student.id, exam.id)"
                                                            >
                                                                {{ exam.name }}
                                                            </div>
                                                        </div>
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
                                    <pagination-component
                                        :is-ajax="true" :data="studentList"
                                        :params="[{ name: 'withMarks', value: true }]"
                                        @update:page-data="updatePageData"
                                    />
                                </div>

                                <!-- MarkSheet List -->
                                <div v-if="markList">
                                    <div v-if="showMarkSheets">
                                        <div class="flex justify-end gap-4">
                                            <t-button id="print-all-button" v-print="{ id: 'all-mark-sheet-wrapper', popTitle: 'MarkSheet' }">
                                                Print All
                                            </t-button>
                                            <t-button id="print-summary-button" v-print="{ id: 'mark-summary-print', popTitle: 'MarkSheet' }">
                                                Print Summary
                                            </t-button>
                                        </div>
                                        <div id="all-mark-sheet-wrapper">
                                            <div
                                                v-for="(studentMarkList, studentId) in _.groupBy(markList, (mark) => mark.student_id)"
                                                :key="`student-${studentId}-marksheet-wrapper`"
                                            >
                                                <MarkSheetLayout
                                                    :print-id="`marksheet-print-student-${studentId}`"
                                                    :student="studentMarkList[0]['student']"
                                                    :grade="grades.find((grade)=>grade.id == studentMarkList[0].grade_id)"
                                                    :exam="exams.find((exam)=>exam.id == studentMarkList[0].exam_id)"
                                                    :marks="studentMarkList"
                                                    :exams="props.exams"
                                                    :gpa-details="gpaDetails"
                                                    :grade-display="gradeDisplay"
                                                    :other-exam-marks="_.groupBy(otherMarkList.filter(studentOtherMark=>studentOtherMark.student_id == studentId && studentOtherMark.grade_id == studentMarkList[0].grade_id), 'exam_id')"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="showSummary" class="mt-5">
                                        <MarksSummaryLayout
                                            :print-id="`mark-summary-print`"
                                            :grade="grades.find((grade)=>grade.id == printSummary.grade_id)"
                                            :exam="exams.find((exam)=>exam.id == printSummary.exam_id)"
                                            :subjects="_.uniqBy(markList.map(eachMark=>{ return eachMark.subject }), 'id')"
                                            :marks="markList"
                                            :gpa-details="gpaDetails"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <t-modal v-model="modalDetails.show" :header="modalDetails.title" @hidden="closeMarksEntryModal()">
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
                        v-for="(subject, index) in subjectsOfGrade"
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

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="grid items-center grid-cols-3">
                    <div class="col-span-2">
                        Absent:
                    </div>
                    <div>
                        <t-input v-model="markForm.absent_days" />
                    </div>
                </div>
                <div class="grid items-center grid-cols-3">
                    <div class="col-span-2">
                        Present:
                    </div>
                    <div>
                        <t-input v-model="markForm.present_days" />
                    </div>
                </div>
            </div>

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
