<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3'

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
                        <form
                            @submit.prevent="examForm.post(route('exams.store'))"
                            class="flex items-end justify-center gap-4"
                        >
                            <div class="w-full max-w-xs form-control">
                                <label class="label">
                                    <span class="label-text">Name</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    v-model="examForm.name"
                                    placeholder="Name"
                                    class="w-full max-w-xs input input-bordered"
                                />
                                <div
                                    v-if="examForm.errors.name"
                                >
                                    {{ examForm.errors.name }}
                                </div>
                            </div>

                            <div class="mt-2">
                                <button
                                    class="btn btn-primary"
                                    :class="{ 'btn-disabled': examForm.processing }"
                                    :disabled="examForm.processing"
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
