<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TButton } from '@variantjs/vue'

const pageTitle = 'Edit Grade'

const props = defineProps({
    grade: Object,
})

const gradeForm = useForm({
    name: props.grade.name,
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
                        <form @submit.prevent="gradeForm.put(route('grades.update', props.grade.id))">
                            <div>
                                <label class="label">
                                    <span class="label-text">Name</span>
                                </label>
                                <t-input
                                    name="name"
                                    v-model="gradeForm.name"
                                    placeholder="Name"
                                />
                                <div
                                    v-if="gradeForm.errors.name"
                                >
                                    {{ gradeForm.errors.name }}
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-8">
                                <t-button :disabled="gradeForm.processing">
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
