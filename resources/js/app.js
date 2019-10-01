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
        userDestinationLocation: {
            lat: 0,
            lng: 0
        },
        carMarkerTimer: null,
        carMarkers: []
    },

    created: function () {
        // every 10 seconds the user location will update
        this.carMarkerTimer = setInterval(this.carMarkerUpdate, 10000);
    },

    mounted: function () {
        this.fetchUser();
        this.carMarkerUpdate();
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
            axios.get('/user_location').then(response => {
                this.carMarkers = response.data.map(key => {
                    return {
                        position: {
                            lat: parseFloat(key.position.lat),
                            lng: parseFloat(key.position.lng)
                        }
                    };
                });
            });
        }
    },

    components: {
        "google-map": require("./components/GoogleMap.vue").default,
        "request-ride": require("./components/RequestRide.vue").default
    }
});
