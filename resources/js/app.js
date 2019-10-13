/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import * as VueGoogleMaps from "vue2-google-maps";

require("./bootstrap");

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyBhCWsRqYhpi1ODCMoxrKbQj9jKs8YBvEs",
        libraries: "places" // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
        // OR: libraries: 'places,drawing,visualization'
        // (as you require)
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: "#app",

    data: {
        mapRef: null,
        user: null,
        userLocation: {
            lat: 0,
            lng: 0
        },
        userTrip: null,
        userDestinationLocation: {
            lat: 0,
            lng: 0
        },
        carMarkerTimer: null,
        carMarkers: [],
        availableTripsTimer: [],
        availableTrips: [],

        directionsDisplay: null,
        tripDriver: null,
    },

    created: function () {
        // every 10 seconds the user location will update
        this.carMarkerTimer = setInterval(this.carMarkerUpdate, 10000);

        // ping the awaiting passengers every 1 second
        this.availableTripsTimer = setInterval(this.getAvailableTrips, 1000);
    },

    mounted: function () {
        this.fetchUser();
        this.carMarkerUpdate();
        this.getAvailableTrips();
    },

    methods: {
        // store user object in the user
        fetchUser: function () {
            axios.get('/me').then(response => {
                this.user = response.data;
            });
        },

        // update the car markers periodically
        carMarkerUpdate: function () {
            axios
                .get('/user_location').then(response => {
                    this.carMarkers = response.data.map(key => {
                        return {
                            position: {
                                lat: parseFloat(key.position.lat),
                                lng: parseFloat(key.position.lng)
                            }
                        };
                    });
                })
                .catch(() => {});
        },

        // get all the available trips
        getAvailableTrips: function () {
            axios
                .get('/trip/available')
                .then(({ data }) => {
                    const currentTrip = data.data.current_trip;
                    if (currentTrip) {
                        // check if the driver id changes at all, if it does then we update map
                        if (!this.directionsDisplay || this.tripDriver !== currentTrip.driver_id) {
                            this.setDirections(
                                currentTrip.pick_up,
                                currentTrip.drop_off,
                                currentTrip.driver_pos,
                                (response) => {
                                    this.userTrip = {
                                        google: response.routes[0].legs[0]
                                    };
                                }
                            );
                            this.directionsNodes = this.user.id !== currentTrip.driver_id ? null : true;
                        }
                        this.tripDriver = currentTrip.driver_id;
                        this.userTrip = currentTrip;
                        this.availableTrips = [];
                    } else {
                        if (this.userTrip !== null) {
                            // clear direcitons if there was an active trip prior
                            this.clearDirections();
                        }
                        this.tripDriver = null;
                        this.userTrip = null;
                        this.availableTrips = data.data;
                    }
                })
                .catch(() => {});
        },

        // set the directions on a map
        setDirections: function (start, finish, driver, callback) {
            this.clearDirections();
            this.directionsDisplay = new google.maps.DirectionsRenderer;
            this.directionsDisplay.setMap(this.mapRef.$mapObject);

            const directionsService = new google.maps.DirectionsService;
            directionsService.route({
                origin: driver || start,
                destination: finish,
                waypoints: driver === null ? null : [{
                    location: new google.maps.LatLng(start.lat, start.lng),
                    stopover: true
                }],
                optimizeWaypoints: driver !== null,
                travelMode: 'DRIVING'
            },
            (response, status) => {
                if (status === 'OK') {
                    this.directionsDisplay.setDirections(response);
                    callback(response);
                }
            });
        },

        // clear map of directions
        clearDirections: function () {
            if (this.directionsDisplay) {
                this.directionsDisplay.setMap(null);
            }
        },
    },

    components: {
        "google-map": require("./components/GoogleMap.vue").default,
        "request-ride": require("./components/RequestRide.vue").default,
        "pending-riders": require("./components/PendingRiders.vue").default,
    }
});
