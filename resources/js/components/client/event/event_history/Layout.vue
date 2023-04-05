<template>
    <div>
        <div class="col-md-3">
            <h3 class="ml-3"><b>Event Sold History</b></h3>
        </div>
        <Table />
    </div>
</template>

<script>
import Table from "./components/Table.vue";
export default {
    components: {
        Table,
    },
};
</script>
