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
        this.tab = this.$route.path.split("/")[7];
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.bagId = this.$route.path.split("/")[8];
    },
    data: () => ({
        tab: null,
        eventId: "",
        sellerName: "",
        bagName: "",
        bagId: "",
    }),
    methods: {
        nextTab(e) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/inside_bag/" +
                    e +
                    "/" +
                    this.bagId,
            });
        },
    },
};
</script>
