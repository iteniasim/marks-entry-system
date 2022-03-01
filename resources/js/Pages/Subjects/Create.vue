<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'

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
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5">
                        <form
                            @submit.prevent="subjectForm.post(route('subjects.store'))"
                            class="flex items-end justify-center gap-4"
                        >
                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">Subject</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    v-model="subjectForm.name"
                                    placeholder="Name"
                                    class="w-full max-w-xs input input-bordered"
                                />
                                <div
                                    v-if="subjectForm.errors.name"
                                >
                                    {{ subjectForm.errors.name }}
                                </div>
                            </div>

                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">Grade</span>
                                </label>
                                <select
                                    name="grade_id"
                                    v-model="subjectForm.grade_id"
                                    class="w-full max-w-xs select select-bordered"
                                >
                                    <option disabled selected>Grade</option>
                                    <option
                                        v-for="(grade, index) in props.grades"
                                        :key="`grade${index}`"
                                        :value="grade.id"
                                    >
                                        {{ grade.name }}
                                    </option>
                                </select>
                                <div
                                    v-if="subjectForm.errors.grade_id"
                                >
                                    {{ subjectForm.errors.grade_id }}
                                </div>
                            </div>

                            <div class="mt-2">
                                <button
                                    class="btn btn-primary"
                                    :class="{ 'btn-disabled': subjectForm.processing }"
                                    :disabled="subjectForm.processing"
                                >
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
