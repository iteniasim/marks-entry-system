<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TButton } from '@variantjs/vue'

const pageTitle = 'Edit Exam'

const props = defineProps({
    exam: Object,
})

const examForm = useForm({
    name: props.exam.name,
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
                                <label class="label">
                                    <span class="label-text">Name</span>
                                </label>
                                <TInput
                                    name="name"
                                    v-model="examForm.name"
                                    placeholder="Name"
                                />
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
