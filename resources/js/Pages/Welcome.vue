<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'

const props = defineProps({
    image: String
})

const loading = ref(false)
const form = useForm({
    code: null
})

function newPast () {
    loading.value = true
    form.post('/pasts', {
        onSuccess: () => {
            loading.value = false
        }
    })
}

</script>

<template>
    <div>
        <div class="h-screen bg-sky-100 flex md:items-center md:justify-center p-0 md:space-x-6 md:p-6">
            <div
                class="bg-blue-800 duration-200 md:rounded-xl flex flex-col h-screen md:h-min w-full md:w-[450px] shrink-0">
                <div class="shrink-0 text-center text-white p-10">
                    <h1 class=" text-2xl mb-2">Welcome to</h1>
                    <h1 class=" text-4xl font-bold">QR & past</h1>
                </div>
                <div class="grow flex flex-row justify-center items-center drop-shadow  drop-shadow-lg">
                    <img :src="image" class="w-72 h-72" alt="">
                </div>
                <form @submit.prevent="newPast" class="shrink-0">
                    <div
                        class="flex flex-col space-y-4 p-4 text-center">
                        <p class="text-center text-white font-semibold">Enter a Code here</p>
                        <input v-model="form.code"
                               required
                               class="bg-blue-200 text-white p-4 rounded-xl
                                flex flex-row space-x-1 items-center justify-center
                                text-blue-800 placeholder-gray-400 font-bold text-center uppercase
                                active:bg-blue-200 focus:outline-none focus:shadow col-span-2 md:col-span-1"
                               placeholder="YOUR CODE"/>
                        <div class="col-span-1 w-full">
                            <div class="flex flex-row space-x-4">
                                <button class="grow bg-blue-600 text-white p-4 rounded-xl
                                    flex flex-row space-x-1 items-center justify-center
                                    active:bg-blue-700 md:disabled:bg-blue-700 md:disabled:text-blue-400 col-span-2 md:col-span-1"
                                        :disabled="!form.code"
                                        type="submit">
                                    <span>Start new QR Past</span>
                                    <i v-if="loading" class="ri-loader-5-line animate-spin"></i>
                                    <i v-else class="ri-add-circle-line"></i>
                                </button>
                                <Link
                                    :href="route('scan')"
                                    type="button"
                                    class="shrink-0 w-14 h-14 md:hidden text-white rounded-xl flex items-center
                                    justify-center bg-blue-600 text-4xl">
                                    <i class="ri-qr-code-line"></i>
                                </Link>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="shrink-0 mb-1 text-gray-300 text-center"> Copyright © 2022 <a class="font-bold"
                                                                                          href="https://witec.dev">WiTec</a>.
                </div>
                <div class="shrink-0 mb-6 text-xs text-gray-300 text-center">
                    <Link class="underline" :href="route('privacy_policy')">Privacy Policy</Link>
                    . /
                    <Link class="underline" :href="route('Terms_and_Conditions')">Terms &amp; Conditions</Link>
                    .
                </div>
            </div>
        </div>
    </div>
</template>
