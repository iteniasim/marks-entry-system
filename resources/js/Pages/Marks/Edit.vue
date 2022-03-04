<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'

const pageTitle = 'Edit Student'

const props = defineProps({
    student: Object,
    grades: Object,
})

const studentForm = useForm({
    first_name: props.student.first_name,
    last_name: props.student.last_name,
    roll_no: props.student.roll_no,
    grade_id: props.student.grade_id,
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
                            @submit.prevent="studentForm.put(route('students.update', student.id))"
                            class="flex items-end justify-center gap-4"
                        >
                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">First name</span>
                                </label>
                                <input
                                    type="text"
                                    name="first_name"
                                    v-model="studentForm.first_name"
                                    placeholder="First name"
                                    class="w-full max-w-xs input input-bordered"
                                />
                                <div
                                    v-if="studentForm.errors.first_name"
                                >
                                    {{ form.errors.first_name }}
                                </div>
                            </div>
                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">Last name</span>
                                </label>
                                <input
                                    type="text"
                                    name="last_name"
                                    v-model="studentForm.last_name"
                                    placeholder="Last name"
                                    class="w-full max-w-xs input input-bordered"
                                />
                                <div
                                    v-if="studentForm.errors.last_name"
                                >
                                    {{ studentForm.errors.last_name }}
                                </div>
                            </div>
                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">Roll No.</span>
                                </label>
                                <input
                                    type="text"
                                    name="roll_no"
                                    v-model="studentForm.roll_no"
                                    placeholder="Roll No."
                                    class="w-full max-w-xs input input-bordered"
                                />
                                <div
                                    v-if="studentForm.errors.roll_no"
                                >
                                    {{ studentForm.errors.roll_no }}
                                </div>
                            </div>
                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">Grade</span>
                                </label>
                                <select
                                    name="grade_id"
                                    v-model="studentForm.grade_id"
                                    class="w-full max-w-xs select select-bordered"
                                >
                                    <option>Select Grade</option>
                                    <option
                                        v-for="(grade, index) in grades"
                                        :key="`grade${index}`"
                                        :value="grade.id"
                                    >
                                        {{ grade.name }}
                                    </option>
                                </select>
                                <div
                                    v-if="studentForm.errors.grade_id"
                                >
                                    {{ studentForm.errors.grade_id }}
                                </div>
                            </div>

                            <div class="mt-2">
                                <button
                                    class="btn btn-primary"
                                    :class="{ 'btn-disabled': studentForm.processing }"
                                    :disabled="studentForm.processing"
                                >
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
