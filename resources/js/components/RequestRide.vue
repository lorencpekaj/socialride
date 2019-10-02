<template>
    <div class="location-search">
        <gmap-autocomplete
            @place_changed="setPlace"
            class="form-control form-control-lg"
            :options="{
                componentRestrictions: {country: 'au'}
            }"
        >
        </gmap-autocomplete>
        <button
            @click="addMarker"
            class="btn btn-lg btn-primary"
        >
            Request Pickup
        </button>
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
            directionsDisplay: null,
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
        setTripData(data) {
            this.tripData = data;
        },
        addMarker() {
            if (this.currentPlace) {
                const marker = {
                    lat: this.currentPlace.geometry.location.lat(),
                    lng: this.currentPlace.geometry.location.lng()
                };

                // clear existing directions
                if (this.directionsDisplay) {
                    this.directionsDisplay.setMap(null);
                }

                this.directionsDisplay = new google.maps.DirectionsRenderer;
                this.directionsDisplay.setMap(this.$root.mapRef.$mapObject);

                const directionsService = new google.maps.DirectionsService;
                directionsService.route({
                    origin: this.$root.userLocation,
                    destination: marker,
                    travelMode: 'DRIVING'
                },
                (response, status) => {
                    if (status === 'OK') {
                        this.directionsDisplay.setDirections(response);
                        this.setTripData(response.routes[0].legs[0]);
                        $(this.$refs.tripInfo.$el).modal('show');
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });

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