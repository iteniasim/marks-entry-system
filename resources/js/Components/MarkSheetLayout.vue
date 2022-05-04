<script setup>
import { TButton } from '@variantjs/vue'
import vPrint from 'vue3-print-nb'
import _ from 'lodash'

const props = defineProps({
    student: Object,
    grade: Object,
    exam: Object,
    marks: Object,
    exams: Object,
    gpaDetails: Object,
    otherExamMarks: Object,
    printId: {
        type: String,
        default: 'print-mark-sheet',
    },
})

const finalMarkForSubject = (subject) => {
    // calculate with current exam marks first
    let subjectMarks = props.marks.find(mark => mark.subject_id == subject.id).obtained_marks * props.exam.final_evaluation_percentage / 100
    // keep adding marks appropriately from other exams
    props.exams.forEach(exam => {
        if (Object.prototype.hasOwnProperty.call(props.otherExamMarks, exam.id)) {
            subjectMarks = subjectMarks + props.otherExamMarks[exam.id].find(mark => mark.subject_id == subject.id).obtained_marks * props.exams.find(examItem => examItem.id == exam.id).final_evaluation_percentage / 100
        }
    })
    return _.round(subjectMarks, 2)
}

const averageGpa = (studentMarkList) => {
    return props.gpaDetails.find(gpaDetail => gpaDetail.lower_mark_limit <= _.meanBy(studentMarkList, 'obtained_marks') && gpaDetail.upper_mark_limit >= _.meanBy(studentMarkList, 'obtained_marks'))
}
</script>

<template>
    <div>
        <div :id="props.printId" class="flex flex-col gap-4" style="page-break-after: always;">
            <div class="px-10 py-10">
                <div>
                    <div>
                        <div class="text-3xl font-black text-center">
                            Central Secondary School For The Deaf
                        </div>
                        <div class="text-2xl font-black text-center">
                            Naxal, Kathmandu
                        </div>
                        <div class="text-xl font-black text-center">
                            {{ props.exam.name }} Examination 2078
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-20">
                        <div>
                            Name : {{ props.student.name }}
                        </div>
                        <div>
                            Class : {{ props.grade.name }}
                        </div>
                        <div>
                            Roll : {{ props.student.roll_no }}
                        </div>
                    </div>

                    <div class="mt-10">
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                            <thead class>
                                <tr class>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">S.N.</th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Subject</th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">GPA</th>
                                    <th v-for="(examTitle, index) in props.exams" :key="`exam-title-${index}`" class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">
                                        {{ examTitle.name }} <span>({{ examTitle.final_evaluation_percentage }}%)</span>
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <template v-if="props.marks.length">
                                    <tr v-for="(mark, index) in props.marks" :key="`mark-${index}`">
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.subject.name }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ mark.mark_grade.gpa }}
                                        </td>
                                        <td v-for="(examMarks, examMarkIndex) in props.exams" :key="`exam-marks-${examMarkIndex}`" class="px-3 py-2 whitespace-no-wrap">
                                            <div v-if="mark.exam_id === examMarks.id">
                                                {{ mark.obtained_marks }}
                                            </div>
                                            <div v-else>
                                                <div v-if="Object.keys(otherExamMarks).length && otherExamMarks.hasOwnProperty(examMarks.id)">
                                                    {{ props.otherExamMarks[examMarks.id].find(otherExamMark=>otherExamMark.subject_id === mark.subject_id).obtained_marks }}
                                                </div>
                                                <div v-else>
                                                    --
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            <div v-if="props.exam.is_final">
                                                {{ finalMarkForSubject(mark.subject) }}
                                            </div>
                                            <div v-else>
                                                --
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between mt-10">
                        <div>
                            Grade Point Average: <span class="font-semibold underline">&nbsp;&nbsp;{{ averageGpa(props.marks).gpa }}&nbsp;&nbsp;</span>
                        </div>
                        <div>
                            Attendance: _________
                        </div>
                    </div>

                    <div class="mt-4">
                        <div>
                            Comments: <span class="font-semibold underline">&nbsp;&nbsp;{{ averageGpa(props.marks).description }}&nbsp;&nbsp;</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 mt-10 border">
                    <div class="mb-4">
                        Note:
                    </div>
                    <div class="grid grid-cols-2 grid-rows-2 gap-4">
                        <div>
                            TH:
                        </div>
                        <div>
                            ABS:
                        </div>
                        <div>
                            PR:
                        </div>
                        <div>
                            W:
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <t-button class="print:hidden" v-print="{ id: props.printId, popTitle: 'MarkSheet' }">
                Print
            </t-button>
        </div>
    </div>
</template>
