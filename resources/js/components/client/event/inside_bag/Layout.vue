<template>
    <div class="row">
        <div class="col-md-6">
            <v-btn
                icon="mdi-arrow-left"
                class="ml-3"
                color="black"
                size="large"
                @click="goToBags"
            ></v-btn>

            <v-btn variant="flat" @click="goToOffline"
                ><u class="text-blue">PENDING TICKETS</u></v-btn
            >
            <!-- <v-btn variant="flat" @click="goToOffline2"
                ><u class="text-blue">PENDING SOLD TICKETS</u></v-btn
            > -->
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
        this.eventId = this.$route.path.split("/")[4];
        this.bagId = this.$route.path.split("/")[8];
    },
    methods: {
        goToOffline() {
            this.$router.push({
                path:
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/inside_bag/all_tickets/offline/" +
                    this.bagId,
            });
        },
        goToBags() {
            this.$router.push({
                path:
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/all_bags/" +
                    this.bagId,
            });
        },
        // goToOffline2() {
        //     this.$router.push({
        //         path:
        //             "/client/branch/" +
        //             this.unitId +
        //             "/" +
        //             this.eventId +
        //             "/event_bags/inside_bag/all_tickets/offline2/" +
        //             this.bagId,
        //     });
        // },
    },
};
</script>
