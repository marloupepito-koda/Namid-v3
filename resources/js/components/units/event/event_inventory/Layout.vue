<template>
    <div class="row">
        <div class="col-md-3" v-if="access5 === 'On'">
            <v-btn variant="flat" @click="goToOffline"
                ><u class="text-blue">OFFLINE PENDINGS</u></v-btn
            >
        </div>
        <div
            :class="
                access5 === 'On'
                    ? 'col-md-3 offset-md-6'
                    : 'col-md-3 offset-md-9'
            "
        >
            <Modal />
        </div>
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
    data: () => ({
        access5: "",
    }),
    components: {
        Drawer,
        Table,
        Modal,
    },
    mounted() {
        this.access5 = JSON.parse(localStorage.getItem("access")).access5;
    },
    methods: {
        goToOffline() {
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4];
            this.eventName = this.$route.path.split("/")[5];
            this.eventId = this.$route.query.event_id;
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_inventory/offline",
                query: { event_id: this.eventId },
            });
        },
    },
};
</script>
