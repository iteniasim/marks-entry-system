<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TButton, TRichSelect } from '@variantjs/vue'

const props = defineProps({
    grades: Object,
})

const pageTitle = 'Create Subject'

const subjectForm = useForm({
    name: null,
    grade_id: null,
    full_marks: null,
    pass_marks: null,
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
                    <div class="px-6 py-5">
                        <form @submit.prevent="subjectForm.post(route('subjects.store'))">
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Subject</span>
                                    </label>
                                    <TInput
                                        name="name"
                                        v-model="subjectForm.name"
                                        placeholder="Name"
                                    />
                                    <div v-if="subjectForm.errors.name">
                                        {{ subjectForm.errors.name }}
                                    </div>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Grade</span>
                                    </label>
                                    <t-rich-select
                                        :options="props.grades"
                                        v-model="subjectForm.grade_id"
                                        name="grade_id"
                                        placeholder="Select Grade"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                    <div v-if="subjectForm.errors.grade_id">
                                        {{ subjectForm.errors.grade_id }}
                                    </div>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Full Marks</span>
                                    </label>
                                    <TInput
                                        name="full_marks"
                                        type="number"
                                        v-model="subjectForm.full_marks"
                                        placeholder="Full Marks"
                                    />
                                    <div v-if="subjectForm.errors.full_marks">
                                        {{ subjectForm.errors.full_marks }}
                                    </div>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Pass Marks</span>
                                    </label>
                                    <TInput
                                        name="pass_marks"
                                        type="number"
                                        v-model="subjectForm.pass_marks"
                                        placeholder="Pass Marks"
                                    />
                                    <div v-if="subjectForm.errors.pass_marks">
                                        {{ subjectForm.errors.pass_marks }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <t-button class="mt-8" :disabled="subjectForm.processing">
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
