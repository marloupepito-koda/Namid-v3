<template>
    <div class="row">
        <div class="col-md-3">
            <h3 class="ml-3"><b>Lists of Event</b></h3>
        </div>
        <div class="col-md-3 offset-md-6"><Modal /></div>
        <div class="col-md-12">
            <Table />
        </div>
    </div>
</template>
<script>
import Table from "./components/Table.vue";
import Modal from "./components/Modal.vue";
export default {
    components: {
        Table,
        Modal,
    },
    data: () => ({
        tab: null,
    }),
};
</script>
