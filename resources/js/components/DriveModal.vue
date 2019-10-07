<template>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request a driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold">You are picking up</p>
                    <p>{{ getPickUpAddress }}</p>
                    <p class="font-weight-bold">Their destination is</p>
                    <p>{{ getDropOffAddress }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="acceptPickup"
                    >
                        Pickup Rider
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'id',
        'passenger',
        'drop_off',
        'pick_up',
    ],

    methods: {
        acceptPickup: function () {
            axios
                .post('/trip/accept/' + this.id, {
                    driver_id: this.$root.user.id
                })
                .then(({ data }) => {
                    $(this.$el).modal('hide');
                });

        }
    },

    computed: {
        getPickUpAddress() {
            return this.pick_up ? this.pick_up.address : '';
        },

        getDropOffAddress() {
            return this.pick_up ? this.drop_off.address : '';
        }
    }
}
</script>