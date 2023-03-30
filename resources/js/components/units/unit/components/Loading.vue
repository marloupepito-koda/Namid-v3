<template>
    <div>Loading...</div>
</template>
<script>
export default {
    mounted() {
        const unitId = this.$route.path.split("/")[3];
        const eventId = this.$route.query.event_id;
        const unitName = this.$route.path.split("/")[4];
        const eventName = this.$route.path.split("/")[5];

        this.$router.push({
            path:
                "/unit/branch/" +
                unitId +
                "/" +
                unitName +
                "/" +
                this.$route.query.where,
        });
    },
};
</script>
