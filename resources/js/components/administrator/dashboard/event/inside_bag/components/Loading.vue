<template>
    <div>Loading...</div>
</template>

<script>
export default {
    mounted() {
        const unitId = this.$route.path.split("/")[3];
        const eventId = this.$route.query.where[0];
        const bagId = this.$route.query.where[1];
        const sellerName = this.$route.query.where[2];
        const ticketPath = this.$route.query.where[3];
        const unitName = this.$route.path.split("/")[4];
        const eventName = this.$route.path.split("/")[5];
        const bagName = this.$route.query.where[4];
        this.$router.push({
            path:
                "/administrator/dashboard/" +
                unitId +
                "/" +
                unitName +
                "/" +
                eventName +
                "/event_bags/inside_bag/" +
                ticketPath,
            query: {
                event_id: [eventId, String(bagId), sellerName, bagName],
            },
        });
    },
};
</script>
