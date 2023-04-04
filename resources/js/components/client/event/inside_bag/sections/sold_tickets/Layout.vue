<template>
    <div><Table :unitList="data" /></div>
</template>

<script>
import Table from "./components/Table.vue";
export default {
    components: {
        Table,
    },
    data: () => ({
        data: [],
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        axios.get("/api/get_all_spots/" + this.unitId).then((res) => {
            this.data = res.data.status;
        });
    },
};
</script>
