<template>
    <GmapMap
        :center="{ lat: -37.81, lng: 144.96 }"
        :zoom="14"
        map-type-id="terrain"
        class="gmap-wrapper"
    >
    </GmapMap>
</template>

<script>
import { gmapApi } from 'vue2-google-maps'

export default {
    computed: {
        google: gmapApi
    }
}
</script>