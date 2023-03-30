<template>
    <div class="row">
        <div class="col-md-4 offset-md-5"><Search /></div>
        <div class="col-md-3"><Modal /></div>
        <div class="col-md-12">
            <Table />
        </div>
        <div class="col-dm-12"></div>
    </div>
</template>

<script>
import Table from "./components/Table.vue";
import Search from "./components/Search.vue";
import Modal from "./components/Modal.vue";
export default {
    components: {
        Table,
        Search,
        Modal,
    },
};
</script>
