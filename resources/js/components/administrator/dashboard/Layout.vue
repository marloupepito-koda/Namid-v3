<template>
    <div class="row">
        <div class="col-md-12 mt-5">
            <Breadcrumbs />
        </div>
        <div class="col-md-3 offset-md-9 mb-5"><Modal /></div>
        <div class="col-md-12">
            <TableVue />
        </div>
    </div>
</template>

<script>
import TableVue from "./components/Table.vue";
import Modal from "./components/Modal.vue";
import Breadcrumbs from "./components/Breadcrumbs.vue";
export default {
    components: {
        TableVue,
        Modal,
        Breadcrumbs,
    },
    data() {
        return {
            dialog: false,
        };
    },
};
</script>
