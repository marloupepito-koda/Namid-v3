<template>
    <v-tabs v-model="tab" bg-color="black" class="m-3" @click="nextTab(tab)">
        <v-tab value="event_inventory">Event Inventory</v-tab>
        <v-tab value="event_bags">Event Bags</v-tab>
        <v-tab value="returned_bags">Returned Bags</v-tab>
        <!-- <v-tab value="daily_breakdown">Daily Seller Breakdown</v-tab> -->
        <v-tab value="logs">Event Logs</v-tab>
        <v-tab value="event_history">Event History</v-tab>
    </v-tabs>

    <v-card-text>
        <v-window v-model="tab">
            <h3><strong>EVENT LEVEL</strong></h3>
            <v-window-item value="event_inventory">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="logs">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="event_bags">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="daily_breakdown">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="returned_bags">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="event_history">
                <router-view></router-view>
            </v-window-item>
        </v-window>
    </v-card-text>
</template>

<script>
import moment from "moment";
export default {
    mounted() {
        this.tab =
            this.$route.path.split("/")[5] === "show_bags"
                ? "event_inventory"
                : this.$route.path.split("/")[5];
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        console.log("wwaaaaa", this.unitId);
    },
    data: () => ({
        tab: null,
        eventId: "",
        unitId: "",
    }),
    methods: {
        nextTab(e) {
            const bagLocation = e == "event_bags" ? e + "/all_bags" : e;

            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/" +
                    bagLocation,
            });
        },
    },
};
</script>
