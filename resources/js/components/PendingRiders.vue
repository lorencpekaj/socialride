<template>
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
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Lorenc needs a driver!</h5>
                    <div>
                        <span class="badge badge-secondary">3.5 km</span>
                        <span class="badge badge-warning">3 minutes</span>
                    </div>
                </div>
                <p class="mb-0 small">Pickup: 12 Waltham Pl, Richmond VIC 3121, Australia</p>
                <p class="mb-0 small">Destination: 12 Waltham Pl, Richmond VIC 3121, Australia</p>
            </a>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            driving: true
        }
    }
}
</script>
