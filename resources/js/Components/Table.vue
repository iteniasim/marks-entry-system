<script setup>
const props = defineProps({
    headers: Array,
    data: Array,
})
</script>
<template>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-100 shadow-sm">
                        <thead class>
                            <tr class>
                                <th class="px-3 py-2 font-semibold text-left bg-gray-100 border-b">Student</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr class>
                                <td class="px-3 py-2 whitespace-no-wrap">Alfonso Bribiesca</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
