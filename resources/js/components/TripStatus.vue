<template>
    <div class="card card-directions shadow-sm" v-if="trip !== null">
        <div class="card-body">
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
                    class="btn btn-sm btn-success"
                    @click="cancelTrip"
                    v-if="isDriver"
                >
                    Complete Trip
                </a>
                <a
                    href="#"
                    class="btn btn-sm btn-danger"
                    @click="cancelTrip"
                    v-if="!isDriver"
                >
                    Cancel
                </a>
            </span>
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