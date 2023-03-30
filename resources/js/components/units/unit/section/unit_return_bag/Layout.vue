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
    data: () => ({
        tab: null,
    }),
};
</script>
