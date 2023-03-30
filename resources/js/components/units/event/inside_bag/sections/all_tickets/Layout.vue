<template>
    <div class="row">
        <div class="col-md-12">
            <Table />
        </div>
    </div>
</template>

<script>
import Table from "./components/Table.vue";
export default {
    components: {
        Table,
    },

    mounted() {
        const eventId = this.$route.query.event_id[0];
        const bagId = this.$route.query.event_id[1];
    },
};
</script>
