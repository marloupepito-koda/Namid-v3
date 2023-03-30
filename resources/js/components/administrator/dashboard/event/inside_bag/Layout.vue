<template>
    <div class="row">
        <div class="col-md-6">
            <v-btn variant="flat" @click="goToOffline"
                ><u class="text-blue">PENDING TICKETS</u></v-btn
            >
            <v-btn variant="flat" @click="goToOffline2"
                ><u class="text-blue">PENDING SOLD TICKETS</u></v-btn
            >
        </div>
        <div class="col-md-3 offset-md-3"><Modal /></div>
        <div class="col-md-12 mt-5">
            <Tabs />
        </div>
    </div>
</template>
<script>
import Tabs from "./components/Tabs.vue";
import Modal from "./components/Modal.vue";
import Search from "./components/Search.vue";
export default {
    components: {
        Tabs,
        Modal,
        Search,
    },
    mounted() {
        this.tab = this.$route.path.split("/")[7];
        this.unitId = this.$route.path.split("/")[3];
        this.unitName = this.$route.path.split("/")[4];
        this.eventName = this.$route.path.split("/")[5];
        this.eventId = this.$route.query.event_id[0];
        this.bagId = this.$route.query.event_id[1];
        this.sellerName = this.$route.query.event_id[2];
        this.bagName = this.$route.query.event_id[3];
    },
    methods: {
        goToOffline() {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_bags/inside_bag/all_tickets/offline",
                query: {
                    event_id: [
                        this.eventId,
                        this.bagId,
                        this.sellerName,
                        this.bagName,
                    ],
                },
            });
        },
        goToOffline2() {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_bags/inside_bag/all_tickets/offline2",
                query: {
                    event_id: [
                        this.eventId,
                        this.bagId,
                        this.sellerName,
                        this.bagName,
                    ],
                },
            });
        },
    },
};
</script>
