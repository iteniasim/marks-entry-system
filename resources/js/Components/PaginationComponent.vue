<script setup>
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
    data: Object,
})
</script>

<template>
    <div v-if="props.data.total" class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
        <div class="flex justify-between flex-1 sm:hidden">
            <Link :href="props.data.prev_page_url" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"> Previous </Link>
            <Link :href="props.data.next_page_url" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"> Next </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ props.data.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ props.data.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ props.data.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link
                        v-for="(link, index) in props.data.links" :key="`pagination-link-${index}`"
                        :href="props.data.links[index].url"
                        :class="{'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : props.data.links[index].active, 'rounded-l-md': index === 0, 'rounded-r-md' : index === 5}"
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50"
                        v-html="props.data.links[index].label"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>
