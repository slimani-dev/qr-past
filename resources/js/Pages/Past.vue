<script setup>
import { onMounted, reactive, ref } from 'vue'
import QRCodeVue3 from 'qrcode-vue3'
import { useForm } from '@inertiajs/inertia-vue3'
import PastFile from '@/Components/PastFile.vue'
import moment from 'moment'
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    past: Object,
    url: String
})

const files = reactive({
    data: props.past.files
})

const countDown = ref('')
const createdAt = moment(props.past.created_at).add(1, 'hour').endOf('hour')
setInterval(function () {
    const currentTime = moment()
    const timeLeft = moment.duration(createdAt.diff(currentTime))
    countDown.value = `${timeLeft.hours()}h ${timeLeft.minutes()}m ${timeLeft.seconds()}s`
}, 1000)


const showQrCode = ref(false)
const loading = ref(false)
const uploadLoading = ref(false)
const deleteLoading = ref(false)

const form = useForm({
    content: props.past.content,
    file: null
})

function submit () {
    loading.value = true
    form.put(`/pasts/${props.past.id}`, {
        onSuccess: () => {
            loading.value = false
        }
    })
}

function uploadFile (e) {
    form.file = e.target.files[0]
    console.log('uploading file')
    uploadLoading.value = true
    form.post(`/pasts/${props.past.id}/file`, {
        onSuccess: () => {
            uploadLoading.value = false
        },
        onFinish: () => {
            form.file = null
        }
    })
}

function deletePast () {
    deleteLoading.value = true
    Inertia.delete(`/pasts/${props.past.id}`)
}

onMounted(() => {
    Echo.channel(`pasts.${props.past.id}`)
        .listen('.past.updated', (e) => {
            form.content = e.content
            files.data = e.files
        })
        .listen('past.deleted', (e) => {
            console.log(e)
            window.location.href = route('thanks')
        })
})
</script>

<template>
    <div>
        <div class="h-screen bg-sky-100 flex md:space-x-6" :class="{'p-6': !showQrCode}">
            <div class="flex grow flex-col h-full space-y-4" :class="{'hidden': showQrCode }">
                <div class="grow flex flex-col items-center justify-center">
                    <textarea
                        @keyup.ctrl.enter="submit"
                        v-model="form.content"
                        class="w-full h-full min-h-[300px] text-5xl flex justify-center items-center bg-transparent border-0 focus:border-0 focus:ring-0 outline-0 focus:outline-0"
                        placeholder="Put Your Text Here"></textarea>
                </div>
                <div v-if="files.data.length" class=" mx-2 text-sm font-bold">Max 4 files ( max size 10Mb )</div>
                <div v-if="files.data.length"
                     class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <PastFile v-for="file in files.data" :key="file.id" :file="file" :past_id="past.id"/>
                </div>
                <div class="shrink-0 grid grid-cols-5 md:grid-cols-2 gap-4">
                    <button
                        class="grow h-14 bg-blue-900 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400 col-span-2 md:col-span-1"
                        @click="submit"
                        :disabled="!form.content"
                    >
                        <span>Send</span>
                        <i v-if="loading" class="ri-loader-5-fill animate-spin"></i>
                        <i v-else class="ri-send-plane-2-line"></i>
                    </button>
                    <button
                        class="grow h-14 bg-blue-900 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400 col-span-2 md:col-span-1"
                        @click="$refs.file.click()"
                    >
                        <span>Upload</span>
                        <i v-if="uploadLoading" class="ri-loader-5-fill animate-spin"></i>
                        <i v-else class="ri-attachment-2"></i>
                    </button>
                    <button
                        @click="showQrCode = true"
                        :class="{'hidden': showQrCode}"
                        class="w-14 h-14 md:hidden text-white shadow-lg rounded-xl flex items-center justify-center bg-blue-900
                        text-4xl">
                        <i class="ri-qr-code-line"></i>
                    </button>
                    <input type="file"
                           ref="file"
                           class="hidden"
                           @input="uploadFile">
                </div>
            </div>
            <div
                class="bg-blue-900 duration-200 md:rounded-xl flex flex-col h-full w-full md:w-[450px] shrink-0"
                :class="{'hidden md:flex ': !showQrCode }">
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
                <div class="text-white p-8 lg:p-12 grow">
                    <h3 class="text-3xl font-bold text-center">QR past</h3>
                    <ul class="list-disc">
                        <li>Scan the QR code and past the text between your devices.</li>
                        <li>Your data will be removed in {{ countDown }}</li>
                        <li>You can upload up to 4 files ( max size 10Mb )</li>
                    </ul>
                </div>
                <div class="shrink-0 grid grid-cols-5 p-4 gap-4">
                    <Link class="bg-blue-700 h-14 col-span-4 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400"
                          :href="route('pasts.index', {'id_to_delete': past.id})">
                        <span>start new QR Past</span>
                        <i class="ri-add-circle-line text-lg ml-1"></i>
                    </Link>
                    <button class="grow h-14 bg-blue-700 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400"
                            @click="deletePast">
                        <i v-if="deleteLoading" class="ri-loader-5-fill animate-spin"></i>
                        <i v-else class="ri-delete-bin-line"></i>
                    </button>
                </div>
                <div class="mb-6 shrink-0 text-gray-300 text-center"> Copyright © 2022 <a
                    href="https://witec.dev">WiTec</a>.
                </div>
            </div>
        </div>
    </div>
</template>
