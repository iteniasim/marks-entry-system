<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { TButton } from '@variantjs/vue'
import vPrint from 'vue3-print-nb'

const pageTitle = 'Marks Details'

const props = defineProps({
    student: Object,
    grade: Object,
    exam: Object,
    marks: Object,
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
                    <div id="printMe" class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2 font-bold">
                            <div>
                                Student Name: {{ props.student.name }}
                            </div>
                            <div>
                                Grade : {{ props.grade.name }}
                            </div>
                            <div>
                                Exam : {{ props.exam.name }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div v-for="(mark, index) in props.marks" :key="`mark-${index}`" class="flex flex-col gap-2">
                                <div>Subject Name : {{ mark.subject.name }}</div>
                                <div>Full Marks: {{ mark.full_marks }}</div>
                                <div>Pass Marks: {{ mark.pass_marks }} </div>
                                <div>Obtained Marks: {{ mark.obtained_marks }} </div>
                            </div>
                        </div>
                    </div>
                    <t-button v-print="{id:'printMe', popTitle:'title'}">
                        print
                    </t-button>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
