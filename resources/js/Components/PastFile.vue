<script setup>

import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    past_id: {
        type: String,
        required: true
    },
    file: {
        type: Object,
        required: true
    }
})

const loading = ref(false)

function deleteMedia () {
    loading.value = true
    Inertia.delete(`/pasts/${props.past_id}/file/${props.file.id}`)
}

</script>

<template>
    <div class="flex flex-row items-center pl-4 py-4 bg-blue-900 h-14 rounded-xl text-white">
        <div class="grow w-[calc(100%-112px)] mr-2">
            <p class="line-clamp-1 mb-0">{{ file.name }}</p>
            <p class="line-clamp-1 text-xs text-blue-300 uppercase">{{ file.type }}</p>
        </div>
        <a :href="file.url" target="_blank" :download="file.name"
           class="active:bg-blue-800 hover:bg-blue-800 p-4 h-14">
            <i class="ri-download-2-fill"></i>
        </a>
        <i v-if="loading" class="ri-loader-5-fill mx-4 animate-spin"></i>
        <button
            v-else
            @click="deleteMedia"
            class="active:bg-blue-800 hover:bg-blue-800 p-4 h-14 rounded-r-xl">
            <i class="ri-delete-bin-fill"></i>
        </button>
    </div>
</template>
