<template>
    <div class="row">
        <div class="col-md-3">
            <h3 class="ml-3"><b>Event Ticket Inventory</b></h3>
        </div>
        <div class="col-md-3 offset-md-6"><Modal /></div>
        <div class="col-md-12">
            <Table />
        </div>
    </div>
</template>

<script>
import Table from "./components/Table.vue";
import Modal from "./components/Modal.vue";
import Drawer from "./components/Drawer.vue";
export default {
    components: {
        Drawer,
        Table,
        Modal,
    },
    methods: {
        goToOffline() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventName = this.$route.path.split("/")[5];
            this.eventId = this.$route.path.split("/")[4];
            this.$router.push({
                path:
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_inventory/offline",
                query: { event_id: this.eventId },
            });
        },
    },
};
</script>
