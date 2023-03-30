<template>
    <v-tabs v-model="tab" bg-color="black" class="m-3" @click="nextTab(tab)">
        <v-tab value="daily_seller_breakdown">Daily Seller Breakdown</v-tab>
    </v-tabs>

    <v-card-text>
        <v-window v-model="tab">
            <h3><strong>Daily Breakdown</strong></h3>
            <v-window-item value="daily_seller_breakdown">
                <router-view></router-view>
            </v-window-item>
        </v-window>
    </v-card-text>
</template>

<script>
export default {
    data: () => ({
        tab: "daily_seller_breakdown",
    }),
};
</script>
