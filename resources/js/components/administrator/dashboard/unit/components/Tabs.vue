<template>
    <v-tabs v-model="tab" bg-color="black" class="m-3" @click="nextTab(tab)">
        <v-tab value="event_list">Unit Event List</v-tab>
        <v-tab value="unit_ticket_inventory">Unit Ticket Inventory</v-tab>
        <!-- <v-tab value="unit_returned_bag">Unit Returned Bag </v-tab> -->
    </v-tabs>

    <v-card-text>
        <v-window v-model="tab">
            <h3><strong>UNIT LEVEL</strong></h3>
            <v-window-item value="event_list">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="unit_ticket_inventory">
                <router-view></router-view>
            </v-window-item>
            <v-window-item value="unit_returned_bag">
                <router-view></router-view>
            </v-window-item>
        </v-window>
    </v-card-text>
</template>

<script>
export default {
    mounted() {
        this.tab = this.$route.path.split("/")[4];
        this.unitId = this.$route.path.split("/")[3];
    },
    data: () => ({
        tab: null,
        unitId: "",
    }),
    methods: {
        nextTab(e) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" + this.unitId + "/" + this.tab,
            });
        },
    },
};
</script>
