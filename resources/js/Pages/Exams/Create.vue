<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TButton } from '@variantjs/vue'

const pageTitle = 'Create Exam'

defineProps({
    grades: Object,
})

const examForm = useForm({
    name: null,
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
                    <div class="px-6 py-5">
                        <form @submit.prevent="examForm.post(route('exams.store'))">
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
