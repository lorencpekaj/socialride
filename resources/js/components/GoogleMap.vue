<template>
    <div>
        <GmapMap
            :center="{ lat: -37.81, lng: 144.96 }"
            :zoom="14"
            map-type-id="terrain"
            class="gmap-wrapper"
        >
            <gmap-marker
                :key="index"
                v-for="(m, index) in this.$root.carMarkers"
                :position="m.position"
                @click="center=m.position"
                :icon="{
                    url: require('../../images/icons/car.jpg'),
                    size: {width: 32, height: 32, f: 'px', b: 'px',},
                    scaledSize: {width: 32, height: 32, f: 'px', b: 'px',},
                }"
            ></gmap-marker>
        </GmapMap>
    </div>
</template>

<script>
import { gmapApi } from 'vue2-google-maps'

export default {
    computed: {
        google: gmapApi
    }
}
</script>