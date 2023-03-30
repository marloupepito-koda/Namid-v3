<template>
    <div>Loading...</div>
</template>
<script>
export default {
    mounted() {
        this.$router.push({
            path: "/administrator/transaction",
            query: {
                searchTransaction: [
                    this.$route.query.searchTransaction[0],
                    this.$route.query.searchTransaction[1],
                    this.$route.query.searchTransaction[2],
                    this.$route.query.searchTransaction[3],
                ],
            },
        });
    },
};
</script>
