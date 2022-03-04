<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TRichSelect, TInput, TButton } from '@variantjs/vue'

const pageTitle = 'Marks Edit'

const props = defineProps({
    students: Object,
    subjects: Object,
    exams: Object,
    grades: Object,
    mark: Object,
})

const studentForm = useForm({
    student_id: props.mark.student_id,
    subject_id: props.mark.subject_id,
    exam_id: props.mark.exam_id,
    grade_id: props.mark.grade_id,
    obtained_marks: props.mark.obtained_marks,
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
                <div class="bg-white shadow-sm overflow-none sm:rounded-lg">
                    <div class="px-6 py-5">
                        <form
                            @submit.prevent="studentForm.post(route('marks.update'))"
                        >
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Student</span>
                                    </label>
                                    <t-rich-select
                                        :options="props.students"
                                        v-model="studentForm.student_id"
                                        name="student_id"
                                        placeholder="Select Student"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Subject</span>
                                    </label>
                                    <t-rich-select
                                        :options="props.subjects"
                                        v-model="studentForm.subject_id"
                                        name="subject_id"
                                        placeholder="Select Subject"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Grades</span>
                                    </label>
                                    <t-rich-select
                                        :options="props.grades"
                                        v-model="studentForm.grade_id"
                                        name="grade_id"
                                        placeholder="Select Grade"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Exam</span>
                                    </label>
                                    <t-rich-select
                                        name="exam_id"
                                        v-model="studentForm.exam_id"
                                        :options="props.exams"
                                        placeholder="Select Exam"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Obtained Marks</span>
                                    </label>
                                    <t-input name="obtained_marks" v-model="studentForm.obtained_marks" />
                                </div>
                            </div>

                            <div class="mt-2">
                                <t-button
                                    class="bg-blue-500"
                                    :class="{ 'opacity-75': studentForm.processing }"
                                    :disabled="studentForm.processing"
                                >
                                    Save
                                </t-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
