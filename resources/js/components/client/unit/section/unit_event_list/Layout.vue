<template>
    <div class="row">
        <div class="col-md-3 offset-md-9"><Modal /></div>
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
