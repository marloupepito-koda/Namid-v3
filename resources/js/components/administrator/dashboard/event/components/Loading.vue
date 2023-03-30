<template>
    <div>Loading...</div>
</template>

<script>
export default {
    mounted() {
        const unitId = this.$route.path.split("/")[3];
        const eventId = this.$route.query.where[1];
        const unitName = this.$route.path.split("/")[4];
        const eventName = this.$route.path.split("/")[5];
        const path = this.$route.query.where[0];
        this.$router.push({
            path:
                "/administrator/dashboard/" +
                unitId +
                "/" +
                unitName +
                "/" +
                eventName +
                "/" +
                path,
            query: {
                event_id: eventId,
                searchDate: this.$route.query.searchDate,
            },
        });
    },
};
</script>
