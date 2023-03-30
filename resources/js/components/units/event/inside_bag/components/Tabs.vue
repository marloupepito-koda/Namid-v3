<template>
    <v-card class="p-0">
        <v-tabs
            density="comfortable"
            v-model="tab"
            bg-color="black"
            @click="nextTab(tab)"
        >
            <v-tab value="all_tickets">All Tickets</v-tab>
            <v-tab value="sold_tickets">Sold Tickets</v-tab>
            <v-tab value="unsold_tickets">Unsold Tickets</v-tab>
        </v-tabs>

        <v-card-text class="p-0">
            <v-window v-model="tab">
                <v-window-item value="all_tickets">
                    <router-view></router-view>
                </v-window-item>

                <v-window-item value="sold_tickets">
                    <router-view></router-view>
                </v-window-item>

                <v-window-item value="unsold_tickets">
                    <router-view></router-view>
                </v-window-item>
            </v-window>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    mounted() {
        this.tab = this.$route.path.split("/")[8];
        this.unitId = this.$route.path.split("/")[3];
        this.unitName = this.$route.path.split("/")[4];
        this.eventName = this.$route.path.split("/")[5];
        this.eventId = this.$route.query.event_id[0];
        this.bagId = this.$route.query.event_id[1];
        this.sellerName = this.$route.query.event_id[2];
        this.bagName = this.$route.query.event_id[3];
    },
    data: () => ({
        tab: null,
        eventId: "",
        sellerName: "",
        bagName: "",
    }),
    methods: {
        nextTab(e) {
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_bags/inside_bag/" +
                    e,
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
