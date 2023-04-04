<template>
    <div class="row">
        <div class="col-md-12">
            <Table :unitList="data" />
        </div>
    </div>
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
