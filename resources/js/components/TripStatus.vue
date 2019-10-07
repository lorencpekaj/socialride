<template>
    <div class="card card-directions" v-if="trip !== null">
        <div class="card-header">
            <span v-if="isDriver">
                You are driving {{ trip.passenger_name }}
            </span>
            <span v-else-if="trip.driver_id === null">
                You are waiting for a driver...
            </span>
            <span v-else>
                You are being driven by {{ trip.driver_name }}
            </span>

            <span class="float-right">
                <a
                    href="#"
                    class="btn btn-sm btn-danger"
                    @click="cancelTrip"
                >
                    Cancel
                </a>
            </span>
        </div>
        <div
            class="card-body"
            ref="directionsRef"
            v-if="isDriver"
        >
            <button
                class="btn btn-block btn-success"
            >
                Complete Trip
            </button>
        </div>
    </div>
</template>

<script>
export default {

    computed: {
        trip() {
            return this.$root.userTrip;
        },

        isDriver() {
            return this.$root.user && this.$root.user.id === this.trip.driver_id;
        },
    },

    methods: {
        cancelTrip: function () {
            this.$root.clearDirections();
            axios.delete('/trip/' + this.trip.id);
        }
    }

}
</script>