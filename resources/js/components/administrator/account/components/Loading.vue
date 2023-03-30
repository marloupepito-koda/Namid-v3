<template>
    <div>Loading...</div>
</template>
<script>
export default {
    mounted() {
        this.$router.push({
            path: "/administrator/accounts",
            // query: {
            //     searchSettlement: [
            //         this.$route.query.searchSettlement[0],
            //         this.$route.query.searchSettlement[1],
            //     ],
            // },
        });
    },
};
</script>
