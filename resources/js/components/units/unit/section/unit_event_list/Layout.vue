<template>
    <div class="row">
        <div class="col-md-3 offset-md-9" v-if="access2 === 'On'">
            <Modal />
        </div>
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
        access2: "",
    }),
    mounted() {
        this.access2 = JSON.parse(localStorage.getItem("access")).access2;
    },
};
</script>
