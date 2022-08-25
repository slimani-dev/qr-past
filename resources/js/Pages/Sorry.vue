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
                class="bg-blue-800 duration-200 md:rounded-xl flex flex-col md:h-min h-full w-full md:w-[450px] shrink-0">
                <div class="shrink-0 text-center text-white p-10 space-y-2">
                    <p class=" text-xl font-bold">We Are sorry to inform you that the number of users currently using the app is at it's limits</p>
                    <p class=" text-xl font-bold">We can't accept any more new users for the moment</p>
                    <p class=" text-xl font-bold">Please come back later</p>
                </div>
                <div class="shrink-0 grid p-4 gap-4">
                    <Link class="grow bg-blue-600 text-white p-4 rounded-xl
                        flex flex-row space-x-1 items-center justify-center
                        active:bg-blue-600 disabled:bg-gray-400 col-span-2 md:col-span-1"
                          :href="route('pasts.index')">
                        <span>Home</span>
                        <i class="ri-qr-code-line"></i>
                    </Link>
                </div>
                <div class="shrink-0 mb-1 text-gray-300 text-center">
                    Copyright © 2022 <a class="font-bold" href="https://witec.dev">WiTec</a>.
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
