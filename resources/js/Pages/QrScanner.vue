<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { QrcodeStream } from 'vue3-qrcode-reader'
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    qr_scan_bg: String
})

const camera = ref('auto')
const open = ref(false)
const pathname = ref('')


const reset = () => {
    camera.value = 'auto'
    open.value = false
}

const onDecode = (decodedString) => {
    console.log(decodedString)
    if (decodedString.match(/^(https:\/\/qr-past\.witec\.net\/c\/)[a-zA-Z0-9]+/)) {
        const url = new URL(decodedString)
        pathname.value = url.pathname
        console.log(url)
    }

    if (decodedString.match(/^(http:\/\/localhost:8000\/c\/)[a-zA-Z0-9]+/)) {
        const url = new URL(decodedString)
        pathname.value = url.pathname
        console.log(url)
    }

    if (decodedString.match(/^(http:\/\/127.0.0.1:8000\/c\/)[a-zA-Z0-9]+/)) {
        const url = new URL(decodedString)
        pathname.value = url.pathname
        console.log(url)
    }
}

const paintOutline = (location, ctx) => {
    let ctxWidth = ctx.canvas.width
    let ctxHeight = ctx.canvas.height

    let sqrSide = (ctxHeight > ctxWidth ? ctxHeight * 375 / 1080 : ctxWidth * 375 / 1920)
    const QrSqr = {
        height: sqrSide,
        width: sqrSide,
        topLeftCorner: {
            x: (ctxWidth - sqrSide) / 2,
            y: (ctxHeight - sqrSide) / 2
        },
        topRightCorner: {
            x: (ctxWidth - sqrSide) / 2 + sqrSide,
            y: (ctxHeight - sqrSide) / 2
        },
        bottomRightCorner: {
            x: (ctxWidth - sqrSide) / 2 + sqrSide,
            y: (ctxHeight - sqrSide) / 2 + sqrSide
        },
        bottomLeftCorner: {
            x: (ctxWidth - sqrSide) / 2,
            y: (ctxHeight - sqrSide) / 2 + sqrSide
        },

        encloses: function encloses (location) {

            return !(this.topLeftCorner.x > location.topLeftCorner.x
                || this.topLeftCorner.y > location.topLeftCorner.y
                || this.topRightCorner.x < location.topRightCorner.x
                || this.topRightCorner.y > location.topRightCorner.y
                || this.bottomRightCorner.x < location.bottomRightCorner.x
                || this.bottomRightCorner.y < location.bottomRightCorner.y
                || this.bottomLeftCorner.x > location.bottomLeftCorner.x
                || this.bottomLeftCorner.y < location.bottomLeftCorner.y)
        }
    }

    const topLeftCorner = location.topLeftCorner,
        topRightCorner = location.topRightCorner,
        bottomRightCorner = location.bottomRightCorner,
        bottomLeftCorner = location.bottomLeftCorner

    if (QrSqr.encloses(location) && pathname.value) {
        ctx.strokeStyle = '#4284F4'
        camera.value = 'off'
        Inertia.get(pathname.value)
    } else {
        ctx.strokeStyle = '#EC4E31'
    }


    ctx.lineWidth = '5'

    ctx.beginPath()
    ctx.moveTo(topLeftCorner.x, topLeftCorner.y)
    ctx.lineTo(topRightCorner.x, topRightCorner.y)
    ctx.lineTo(bottomRightCorner.x, bottomRightCorner.y)
    ctx.lineTo(bottomLeftCorner.x, bottomLeftCorner.y)
    ctx.lineTo(topLeftCorner.x, topLeftCorner.y)
    ctx.closePath()
    ctx.stroke()

}

</script>


<template>
    <div class="h-screen relative">

        <qrcode-stream
            class="inset-0"
            :track="paintOutline"
            :camera="camera"
            @decode="onDecode">
            <img class="w-full h-full object-cover" :src="qr_scan_bg" alt="">
        </qrcode-stream>

        <div class="absolute top-0 left-0 right-0 px-4 pt-6 flex flex-row space-x-2 items-center drop-shadow">
            <Link :href="route('welcome')"
                  class="shrink-0 w-14 h-14 md:hidden text-white rounded-xl flex items-center
                                    justify-center bg-blue-600 text-2xl shadow-xl">
                <i class="ri-arrow-left-s-line"></i>
            </Link>
            <div class="grow font-bold text-xl">
            </div>
            <button @click="reset"
                    v-if="camera === 'off'"
                    class="shrink-0 w-14 h-14 md:hidden text-white rounded-xl flex items-center
                                    justify-center bg-blue-600 text-2xl shadow-xl">
                <i class="ri-restart-line"></i>
            </button>
        </div>

    </div>

</template>
