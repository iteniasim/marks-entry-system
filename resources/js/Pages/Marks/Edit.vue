<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TRichSelect, TInput, TButton } from '@variantjs/vue'
import { computed, watch } from 'vue'

const pageTitle = 'Marks Edit'

const props = defineProps({
    students: Object,
    subjects: Object,
    exams: Object,
    grades: Object,
    mark: Object,
})

const markForm = useForm({
    student_id: props.mark.student_id,
    subject_id: props.mark.subject_id,
    exam_id: props.mark.exam_id,
    grade_id: props.mark.grade_id,
    full_marks: props.mark.full_marks,
    pass_marks: props.mark.pass_marks,
    obtained_marks: props.mark.obtained_marks,
})

const displaySubjects = computed(() => {
    if (markForm.student_id === null) {
        return {}
    }
    let selectedStudent = props.students.find((student) => student.id == markForm.student_id)
    return props.subjects.filter(subject => subject.grade_id === selectedStudent.grade_id)
})

watch(markForm, () => {
    let selectedStudent = props.students.find((student) => student.id == markForm.student_id)
    markForm.grade_id = selectedStudent.grade_id
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
                        <form @submit.prevent="markForm.put(route('marks.update', props.mark.id))">
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Student</span>
                                    </label>
                                    <t-rich-select
                                        :disabled="true"
                                        class="opacity-50"
                                        :options="props.students"
                                        v-model="markForm.student_id"
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
                                        :disabled="true"
                                        class="opacity-50"
                                        :options="displaySubjects"
                                        v-model="markForm.subject_id"
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
                                        :disabled="true"
                                        class="opacity-50"
                                        :options="props.grades"
                                        v-model="markForm.grade_id"
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
                                        :disabled="true"
                                        class="opacity-50"
                                        name="exam_id"
                                        v-model="markForm.exam_id"
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
                                    <t-input name="obtained_marks" v-model="markForm.obtained_marks" />
                                </div>
                            </div>

                            <div class="mt-2">
                                <t-button :disabled="markForm.processing">
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
