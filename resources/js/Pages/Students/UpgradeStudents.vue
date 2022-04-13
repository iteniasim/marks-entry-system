<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TCheckbox, TRichSelect, TButton } from '@variantjs/vue'
import axios from 'axios'
import debounce from 'lodash/debounce'
import { ref, watch } from 'vue'

const pageTitle = 'Upgrade Students'

const props = defineProps({
    grades: Object,
})

const upgradeForm = useForm({
    fromGradeId: null,
    toGradeId: null,
    studentIdsToUpgrade: [],
})

const studentsInGrade = ref()

const fetchGradeStudents = () => {
    axios.get(route('gradeData', { grade: upgradeForm.fromGradeId })).then(res => {
        studentsInGrade.value = res.data.students
        upgradeForm.studentIdsToUpgrade = res.data.students.map(student => student.id)
    })
}

watch(() => upgradeForm.fromGradeId, debounce(fetchGradeStudents, 2000))

const addOrRemoveStudentIdFromForm = (studentId) => {
    if (upgradeForm.studentIdsToUpgrade.includes(studentId)) {
        upgradeForm.studentIdsToUpgrade = upgradeForm.studentIdsToUpgrade.filter(eachItem => eachItem !== studentId)
    } else {
        upgradeForm.studentIdsToUpgrade.push(studentId)
    }
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
                <div class="grid grid-cols-2 gap-12 mb-8">
                    <div>
                        <div class="inline-block">
                            From:
                        </div>
                        <t-rich-select
                            :options="props.grades"
                            v-model="upgradeForm.fromGradeId"
                            placeholder="Select Grade"
                            value-attribute="id"
                            text-attribute="name"
                        />
                    </div>
                    <div>
                        <div class="inline-block">
                            To:
                        </div>
                        <t-rich-select
                            :options="props.grades"
                            v-model="upgradeForm.toGradeId"
                            placeholder="Select Grade"
                            value-attribute="id"
                            text-attribute="name"
                        />
                    </div>
                </div>
                <div v-if="studentsInGrade">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b" />
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Name</th>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Roll</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr
                                v-for="(student, index) in studentsInGrade"
                                :key="`student-${index}`"
                            >
                                <td class="px-3 py-2 whitespace-no-wrap">
                                    <label class="flex items-center ml-2">
                                        <TCheckbox @change="addOrRemoveStudentIdFromForm(student.id)" :checked="upgradeForm.studentIdsToUpgrade.includes(student.id)" />
                                    </label>
                                </td>
                                <td class="px-3 py-2 whitespace-no-wrap">
                                    {{ student.name }}
                                </td>
                                <td class="px-3 py-2 whitespace-no-wrap">
                                    {{ student.roll_no }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex items-center justify-end mt-10">
                        <t-button @click="upgradeForm.post(route('upgradeStudentsOfGrade'))">
                            Save
                        </t-button>
                    </div>
                </div>
                <div v-else>
                    No Students Available
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
