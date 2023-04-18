<template>
    <v-btn icon @click="startUpload">
        <v-tooltip text="Click to Upload" activator="parent" location="bottom">
            <template v-slot:activator="{ props }">
                <v-icon size="x-large">mdi-cloud-upload-outline</v-icon>
            </template>
            <v-alert
                v-if="notification !== false"
                v-model="notification"
                type="error"
                dismissible
            >
                No Internet Connection!
            </v-alert>
            <div v-else>Click to Download</div>
        </v-tooltip>
    </v-btn>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            unitId: "",
            eventId: "",
            internet: "",
            notification: false,
        };
    },
    mounted() {
        this.mount();
    },
    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.mount();
            }
        );
    },
    methods: {
        mount() {
            this.unitId = this.$route.params.unitid;
            this.eventId = this.$route.params.eventid;
        },
        startUpload() {
            this.internet = localStorage.getItem("internet");
            if (this.internet !== "offline") {
                axios
                    .get("/api/get_events_in_unit/" + this.unitId)
                    .then((res) => {
                        res.data.status.map((aaa) =>
                            this.getEventBags(aaa.unitid, aaa.id)
                        );
                    });
            } else {
                this.notification = true;
            }
        },
        getEventBags(unitid, eventid) {
            axios
                .get("/api/get_event_bags/" + unitid + "/" + eventid + "/all")
                .then((res) => {
                    res.data.status.map((aaa) =>
                        this.uploadTicketSoldInBag(
                            aaa.unitid,
                            aaa.eventid,
                            aaa.id
                        )
                    );
                });
        },
        uploadTicketSoldInBag(unitid, eventid, bagid) {
            const get_tickets_in_bag = JSON.parse(
                localStorage.getItem(
                    "get_tickets_in_bag" + unitid + eventid + bagid
                )
            );
            if (get_tickets_in_bag !== null) {
                axios
                    .post("/uploading_ticket_in_bag", {
                        data: get_tickets_in_bag,
                    })
                    .then((res) => {
                        localStorage.removeItem(
                            "get_tickets_in_bag" + unitid + eventid + bagid
                        );
                        this.$router.push({
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                    });
            }
        },
    },
};
</script>
