<template>
    <v-card>
        <v-tabs
            density="comfortable"
            v-model="tab"
            bg-color="black"
            @click="nextTab(tab)"
        >
            <v-tab value="all_bags">All Bags</v-tab>
            <v-tab value="active_bags">Active Bags</v-tab>
            <v-tab value="not_active_bags">Not Active Bag</v-tab>
        </v-tabs>

        <v-card-text>
            <v-window v-model="tab">
                <v-window-item value="all_bags">
                    <router-view></router-view>
                </v-window-item>

                <v-window-item value="active_bags">
                    <router-view></router-view>
                </v-window-item>

                <v-window-item value="not_active_bags">
                    <router-view></router-view>
                </v-window-item>
            </v-window>
        </v-card-text>
    </v-card>
</template>

<script>
import moment from "moment";
export default {
    mounted() {
        this.tab = this.$route.path.split("/")[6];
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
    },
    data: () => ({
        tab: null,
        eventId: "",
    }),
    methods: {
        nextTab(e) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/" +
                    e,
            });
        },
    },
};
</script>
