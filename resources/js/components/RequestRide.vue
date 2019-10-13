<template>
    <div class="location-search" v-if="this.$root.userTrip === null">
        <div class="input-group shadow-sm">
            <gmap-autocomplete
                @place_changed="setPlace"
                class="form-control form-control-lg"
                placeholder="Where do you want to go?"
                :options="{
                    componentRestrictions: {country: 'au'}
                }"
            >
            </gmap-autocomplete>
            <div class="input-group-append">
                <button
                    @click="addMarker"
                    class="btn btn-lg btn-primary m-0"
                >
                    Request Pickup
                </button>
            </div>
        </div>
        <trip-modal
            ref="tripInfo"
            v-bind="tripData"
        ></trip-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentPlace: null,
            userLocationTimer: null,
            tripData: {},
        };
    },

    created() {
        // every 60 seconds the user location will update
        this.userLocationTimer = setInterval(this.geolocate, 60000);
    },

    mounted() {
        this.geolocate();
    },

    components: {
        "trip-modal": require("./TripModal.vue").default,
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

                this.$root.setDirections(
                    this.$root.userLocation,
                    marker,
                    null,
                    (response) => {
                        this.tripData = response.routes[0].legs[0];
                        $(this.$refs.tripInfo.$el).modal('show');
                    }
                );

                this.$root.userDestinationLocation = marker;
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