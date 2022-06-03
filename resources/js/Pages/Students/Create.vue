<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { TInput, TRichSelect, TButton } from '@variantjs/vue'

const pageTitle = 'Create Student'

const props = defineProps({
    grades: Object,
})

const studentForm = useForm({
    name: null,
    roll_no: null,
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
                        <form @submit.prevent="studentForm.post(route('students.store'))">
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Name</span>
                                    </label>
                                    <t-input name="name" v-model="studentForm.name" placeholder="Name" />
                                    <div
                                        v-if="studentForm.errors.name"
                                    >
                                        {{ studentForm.errors.name }}
                                    </div>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Roll No.</span>
                                    </label>
                                    <t-input name="roll_no" v-model="studentForm.roll_no" placeholder="Roll No" />
                                    <div
                                        v-if="studentForm.errors.roll_no"
                                    >
                                        {{ studentForm.errors.roll_no }}
                                    </div>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Grade</span>
                                    </label>
                                    <t-rich-select
                                        :options="props.grades"
                                        v-model="studentForm.grade_id"
                                        name="grade_id"
                                        placeholder="Select Grade"
                                        value-attribute="id"
                                        text-attribute="name"
                                    />
                                    <div
                                        v-if="studentForm.errors.grade_id"
                                    >
                                        {{ studentForm.errors.grade_id }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end">
                                <t-button class="mt-8" :disabled="studentForm.processing">
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
