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
                            <div>
                                <label class="label">
                                    <span class="label-text">Subject</span>
                                </label>
                                <TInput
                                    name="name"
                                    v-model="subjectForm.name"
                                    placeholder="Name"
                                />
                                <div
                                    v-if="subjectForm.errors.name"
                                >
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
                                <div
                                    v-if="subjectForm.errors.grade_id"
                                >
                                    {{ subjectForm.errors.grade_id }}
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
