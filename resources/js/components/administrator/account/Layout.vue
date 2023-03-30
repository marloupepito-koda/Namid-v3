<template>
    <div class="row">
        <div class="col-md-3 mt-4">
            <Modal />
        </div>
        <div class="col-md-12"><Table /></div>
    </div>
</template>

<script>
import Modal from "./components/Modal.vue";
import Table from "./components/Table.vue";
export default {
    components: {
        Modal,
        Table,
    },
};
</script>
