<template>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Request a driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold">You are requesting pickup from</p>
                    <p>{{ start_address }}</p>
                    <p class="font-weight-bold">Your destination is</p>
                    <p>{{ end_address }}</p>

                    <p class="text-info">Your travel of {{ distanceText }} will take {{ durationText }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="requestRide"
                    >
                        Request Ride
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'distance',
        'duration',
        'end_address',
        'end_location',
        'start_address',
        'start_location',
    ],

    computed: {
        distanceText() {
            return this.distance ? this.distance.text : '';
        },
        durationText() {
            return this.duration ? this.duration.text : '';
        }
    },

    methods: {
        requestRide: function () {
            axios
                .post('/trip/request_pickup', {
                    'distance': this.distance.value,
                    'duration': this.duration.value,
                    'pick_up': {
                        'lat': this.start_location.lat(),
                        'lng': this.start_location.lng(),
                        'address': this.start_address,
                    },
                    'drop_off': {
                        'lat': this.end_location.lat(),
                        'lng': this.end_location.lng(),
                        'address': this.end_address,
                    }
                })
                .catch((error) => {
                    const response = error.response.data;
                    if (response.success === false) {
                        window.alert(response.errors);
                    }
                });
        }
    }
}
</script>