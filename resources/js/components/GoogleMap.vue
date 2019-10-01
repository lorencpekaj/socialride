<template>
    <div>
        <GmapMap
            ref="mapRef"
            :center="$root.userLocation"
            :zoom="12"
            map-type-id="terrain"
            class="gmap-wrapper"
        >
            <!-- driver locations -->
            <gmap-marker
                :key="index"
                v-for="(m, index) in this.$root.carMarkers"
                :position="m.position"
                @click="center=m.position"
                :icon="{
                    url: require('../../images/icons/car.jpg'),
                    size: { width: 32, height: 32, f: 'px', b: 'px' },
                    scaledSize: { width: 32, height: 32, f: 'px', b: 'px' },
                }"
            ></gmap-marker>

            <!-- user location -->
            <gmap-marker
                :position="$root.userLocation">
            </gmap-marker>
        </GmapMap>
    </div>
</template>

<script>
import { gmapApi } from 'vue2-google-maps'

export default {
    mounted() {
        this.$root.mapRef = this.$refs.mapRef;
    },
    computed: {
        google: gmapApi,
    }
}
</script>