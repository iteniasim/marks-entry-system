<script setup>
import { TButton } from '@variantjs/vue'
import vPrint from 'vue3-print-nb'
import _ from 'lodash'

const props = defineProps({
    grade: Object,
    exam: Object,
    subjects: Object,
    marks: Object,
    gpaDetails: Object,
    printId: {
        type: String,
        default: 'print-summary',
    },
})

const averageGpa = (studentMarkList) => {
    return props.gpaDetails.find(gpaDetail => gpaDetail.lower_mark_limit <= _.meanBy(studentMarkList, 'obtained_marks') && gpaDetail.upper_mark_limit >= _.meanBy(studentMarkList, 'obtained_marks'))
}

const averagePercentage = (studentMarkList) => {
    return  _.round((_.sumBy(studentMarkList, 'obtained_marks') / _.sumBy(studentMarkList, 'full_marks')) * 100, 2)
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
                        <div class="text-lg font-semibold text-center">
                            Class : {{ props.grade.name }}
                        </div>
                    </div>

                    <div class="mt-10">
                        <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                            <thead class>
                                <tr class>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Roll</th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Student</th>
                                    <th
                                        v-for="(subject, index) in props.subjects"
                                        :key="`subject-index-${index}-id-${subject.id}-header`"
                                        class="px-3 py-2 font-semibold text-left bg-gray-100 border-b"
                                    >
                                        {{ subject.name }}
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Percentage</th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Rank</th>
                                    <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Attendance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <template v-if="props.marks.length">
                                    <tr v-for="(studentMarkGroup, studentId) in _.groupBy(props.marks, 'student_id')" :key="`student-mark-body-${studentId}`">
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ studentMarkGroup[0].student.roll_no }}
                                        </td>
                                        <td class="px-3 py-2 whitespace-no-wrap">
                                            {{ studentMarkGroup[0].student.name }}
                                        </td>
                                        <td
                                            class="px-3 py-2 whitespace-no-wrap"
                                            v-for="(subject, index) in props.subjects"
                                            :key="`subject-index-${index}-id-${subject.id}-header`"
                                        >
                                            {{ studentMarkGroup.find(markItem=>markItem.subject_id == subject.id).obtained_marks }}
                                        </td>
                                        <td>
                                            {{ averagePercentage(studentMarkGroup) }}
                                        </td>
                                        <td>
                                            Rank
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <t-button class="print:hidden" v-print="{ id: props.printId, popTitle: 'MarkSheet Summary' }">
                Print Summary
            </t-button>
        </div>
    </div>
</template>
