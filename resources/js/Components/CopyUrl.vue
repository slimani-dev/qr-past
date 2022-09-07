<script setup>
import { copyText } from 'vue3-clipboard'
import { ref } from 'vue'


const props = defineProps({
    url: {
        type: Object,
        required: true
    }
})

const copied = ref(false)

const doCopy = () => {
    copyText(props.url, undefined, (error, event) => {
        if (!error) {
            copied.value = true
        }
    })
}
</script>


<template>
    <button class="grow h-14 bg-blue-600 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-600"
            @click="doCopy">
        <i v-if="copied" class="ri-checkbox-circle-line"></i>
        <i v-else class="ri-file-copy-line"></i>
    </button>
</template>
