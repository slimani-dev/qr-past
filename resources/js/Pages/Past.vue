<script setup>
import { onMounted, reactive, ref } from 'vue'
import QRCodeVue3 from 'qrcode-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    past: Object,
    url: String
})

const showQrCode = ref(false)
const loading = ref(false)
const form = reactive({
    content: props.past.content
})

const onSuccess = () => {
    loading.value = false
}

function submit () {
    loading.value = true
    Inertia.put(`/pasts/${props.past.id}`, form, {
        onSuccess
    })
}

onMounted(() => {
    Echo.channel(`pasts.${props.past.id}`)
        .listen('.pasts.updated', (e) => {
            form.content = e.content
        })
})
</script>

<template>
    <div>
        <div class="h-screen bg-sky-100 flex md:space-x-6" :class="{'p-6': !showQrCode}">
            <div class="flex grow flex-col h-full" :class="{'hidden': showQrCode }">
                <div class="grow flex flex-col items-center justify-center">
                    <textarea
                        v-model="form.content"
                        class="w-full h-full text-5xl flex justify-center items-center bg-transparent border-0 focus:border-0 focus:ring-0 outline-0 focus:outline-0"
                        placeholder="Put Your Text Here"></textarea>
                </div>
                <div class="shrink-0 space-x-4 flex flex-row">
                    <button
                        class="grow bg-blue-900 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400"
                        @click="submit"
                        :disabled="!form.content"
                    >
                        <span>Send</span>
                        <i v-if="loading" class="ri-loader-5-fill animate-spin"></i>
                        <i v-else class="ri-send-plane-2-line"></i>
                    </button>
                    <button
                        class="grow bg-blue-900 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400"
                        disabled
                    >
                        <span>Upload a file</span>
                        <i class="ri-attachment-2"></i>
                    </button>
                </div>
            </div>
            <div
                class="bg-blue-900 duration-200 rounded-xl flex flex-col h-full w-full md:w-[450px] shrink-0"
                :class="{'hidden md:block ': !showQrCode }">
                <div class="flex justify-end">
                    <button
                        @click="showQrCode = false"
                        class="w-16 md:invisible h-16 text-white rounded-full flex items-center justify-center bg-blue-900 text-3xl">
                        <i class="ri-close-line"></i></button>
                </div>
                <div class="pt-1  flex justify-center items-center">
                    <QRCodeVue3
                        :height="300"
                        :width="300"
                        :value="url"
                        :imageOptions="{ margin: 30 }"
                        :dotsOptions="{  color: '#32138D'}"
                        :backgroundOptions="{ color: '#ffffff' }"
                        :cornersSquareOptions="{ color: '#32138D' }"
                        :cornersDotOptions="{ color: '#32138D' }"
                        myclass="p-2 bg-white"
                    />
                </div>
                <div class="text-white p-8 lg:p-12 grow"><h3 class="text-3xl font-bold text-center mb-4">QR past</h3>
                    <p>scan the QR code and past the text between your devices.</p><br>
                    <p> your data will be removed in 24h </p></div>
                <div class="md:hidden mb-6 shrink-0 text-gray-300 text-center"> Copyright © 2022 <a
                    href="https://witec.dev">WiTec</a>.
                </div>
            </div>
            <button
                @click="showQrCode = true"
                :class="{'hidden': showQrCode}"
                class="w-14 h-14 md:hidden text-white shadow-lg rounded-xl flex items-center justify-center bg-blue-900
                fixed bottom-24 right-6 text-4xl">
                <i class="ri-qr-code-line"></i></button>
        </div>
    </div>
</template>
