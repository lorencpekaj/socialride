<template>
    <div class="location-search">
        <gmap-autocomplete
            @place_changed="setPlace"
            class="form-control form-control-lg">
        </gmap-autocomplete>
        <button
            @click="addMarker"
            class="btn btn-lg btn-primary"
        >
            Request Pickup
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // default to Montreal to keep it simple
            // change this to whatever makes sense
            center: { lat: -37.81, lng: 144.96 },
            places: [],
            currentPlace: null,
            userLocationTimer: null
        };
    },

    props: ['driving'],

    created() {
        // every 60 seconds the user location will update
        this.userLocationTimer = setInterval(this.geolocate, 60000);
    },

    mounted() {
        this.geolocate();
    },

    methods: {
        // receives a place object via the autocomplete component
        setPlace(place) {
            this.currentPlace = place;
        },
        addMarker() {
            if (this.currentPlace) {
                const marker = {
                    lat: this.currentPlace.geometry.location.lat(),
                    lng: this.currentPlace.geometry.location.lng()
                };
                this.$root.carMarkers.push({ position: marker });
                this.places.push(this.currentPlace);
                this.center = marker;
                this.currentPlace = null;
            }
        },
        geolocate: function() {
            navigator.geolocation.getCurrentPosition(position => {
                // store the user location for oneself
                this.$root.userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // post the user location to the database
                axios.post('/user_location', {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                });
            });
        }
    }
};
</script>