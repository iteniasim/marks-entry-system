<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TButton, TCheckbox } from '@variantjs/vue'

const pageTitle = 'Edit Exam'

const props = defineProps({
    exam: Object,
})

const examForm = useForm({
    name: props.exam.name,
    finalEvaluationPercentage: props.exam.final_evaluation_percentage,
    isFinal: props.exam.is_final ? true : false,
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
                        <form @submit.prevent="examForm.put(route('exams.update', props.exam.id))">
                            <div>
                                <div>
                                    <div>
                                        <label class="label">
                                            <span class="label-text">Name</span>
                                        </label>
                                        <TInput
                                            name="name"
                                            v-model="examForm.name"
                                            placeholder="Name"
                                        />
                                    </div>

                                    <div class="grid grid-cols-2 mt-5">
                                        <div class="flex flex-col justify-center">
                                            <label class="label">
                                                <span class="label-text">Final Evaluation Percentage</span>
                                            </label>
                                            <TInput
                                                name="finalEvaluationPercentage"
                                                v-model="examForm.finalEvaluationPercentage"
                                                placeholder="Final Evaluation Percentage"
                                            />
                                        </div>
                                        <div class="flex flex-col justify-center">
                                            <label class="flex items-center ml-2">
                                                <TCheckbox name="is_final" v-model="examForm.isFinal" />
                                                <span class="ml-2 text-sm">Is Final Term</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="examForm.errors.name"
                                >
                                    {{ examForm.errors.name }}
                                </div>
                            </div>

                            <div class="mt-2">
                                <t-button :disabled="examForm.processing">
                                    Update
                                </t-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
