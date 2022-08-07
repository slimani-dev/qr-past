<script setup>
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
    image: String
})

</script>

<template>
    <div>
        <div class="h-screen bg-sky-100 flex md:items-center md:justify-center md:space-x-6 md:p-6">
            <div
                class="bg-blue-900 duration-200 md:rounded-xl flex flex-col md:h-min h-full w-full md:w-[450px] shrink-0">
                <div class="grow text-center text-white p-10">
                    <h1 class=" text-4xl font-bold">Thank you for using QR & past</h1>
                </div>
                <div class="flex flex-row justify-center drop-shadow p-10 drop-shadow-lg">
                    <div class="aspect-1">
                        <img :src="image" class="w-full h-full" alt="">
                    </div>
                </div>
                <div class="shrink-0 grid p-4 gap-4">
                    <Link class="grow bg-blue-700 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-700 disabled:bg-gray-400 col-span-2 md:col-span-1"
                          :href="route('pasts.index')">
                        <span>start new QR Past</span>
                        <i class="ri-add-circle-fill"></i>
                    </Link>
                </div>
                <div class="mb-6 shrink-0 text-gray-300 text-center"> Copyright © 2022 <a
                    href="https://witec.dev">WiTec</a>.
                </div>
            </div>
        </div>
    </div>
</template>
