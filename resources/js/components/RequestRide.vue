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
        <trip-modal ref="tripInfo" :trip.sync="directionsData"></trip-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            currentPlace: null,
            userLocationTimer: null,
            directionsData: null,
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
                const directionsService = new google.maps.DirectionsService;
                const directionsDisplay = new google.maps.DirectionsRenderer;
                const tripInfoModal = this.$refs.tripInfo;
                directionsDisplay.setMap(this.$root.mapRef.$mapObject);

                directionsService.route({
                    origin: this.$root.userLocation,
                    destination: marker,
                    travelMode: 'DRIVING'
                }, function(response, status) {
                    if (status === 'OK') {
                        // $('#distance').text(directionsResult.routes[0].legs[0].distance.text);
                        // $('#duration').text(directionsResult.routes[0].legs[0].duration.text);

                        this.directionsData = response.routes[0].legs[0];
                        // tripInfoModal.$props.trip = this.directionsData;
                        $(tripInfoModal.$el).modal('show');
                        console.log(this.directionsData);
                        directionsDisplay.setDirections(response);

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