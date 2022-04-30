<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import MarkSheetLayout from '../../Components/MarkSheetLayout.vue'

const pageTitle = 'Marks Details'

const props = defineProps({
    student: Object,
    grade: Object,
    exam: Object,
    marks: Object,
    exams: Object,
    averageGpa: Object,
    otherExamMarks: Object,
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
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <MarkSheetLayout
                        :student="props.student"
                        :grade="props.grade"
                        :exam="props.exam"
                        :marks="props.marks"
                        :exams="props.exams"
                        :average-gpa="props.averageGpa"
                        :other-exam-marks="props.otherExamMarks"
                    />
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
