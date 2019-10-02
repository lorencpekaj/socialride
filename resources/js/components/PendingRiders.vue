<template>
    <div>
        <div class="pending-riders-wrap">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card card-passengers">
                            <div class="card-header">
                                Passengers awaiting for a driver
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm float-right"
                                    @click="driving = !driving"
                                >
                                    {{ driving ? 'Hide All' : 'Show All' }}
                                </button>
                            </div>

                            <ul class="list-group list-group-passengers" v-if="driving">
                                <a href="#"
                                   class="list-group-item list-group-item-action flex-column align-items-start"
                                   v-for="trip in trips"
                                   :key="trip.id"
                                   @click="selectTrip(trip)"
                                >
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ trip.passenger.name }} needs a driver!</h5>
                                        <div>
                                            <span class="badge badge-secondary">
                                                {{ distanceFormat(trip.distance) }}
                                            </span>
                                            <span class="badge badge-warning">
                                                {{ durationFormat(trip.duration) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mb-0 small">
                                        Pickup: {{ trip.pick_up.address }}
                                    </p>
                                    <p class="mb-0 small">
                                        Destination: {{ trip.drop_off.address }}
                                    </p>
                                </a>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <drive-modal ref="driveModal" v-bind="selectedTrip"></drive-modal>
    </div>
</template>

<script>
var moment = require('moment');
export default {
    data() {
        return {
            driving: true,
            selectedTrip: null,
        }
    },

    computed: {
        trips() {
            return this.$root.availableTrips;
        },
    },

    methods: {
        distanceFormat(distance) {
            return (distance / 1000).toFixed(1) + ' km';
        },

        durationFormat(duration) {
            const startDate = moment().add(duration, 'seconds');
            const diffMinutes = moment.duration(startDate.diff(moment())).asMinutes();
            return diffMinutes.toFixed(1) + ' mins';
        },

        selectTrip(trip) {
            this.selectedTrip = trip;
            $(this.$refs.driveModal.$el).modal('show');
        }
    },

    components: {
        "drive-modal": require("./DriveModal.vue").default,
    },
}
</script>
