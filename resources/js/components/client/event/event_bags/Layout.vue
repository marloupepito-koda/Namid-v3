<template>
    <div class="row">
        <div class="col-md-3">
            <h3 class="ml-3"><b>Event Lists of Bags</b></h3>
        </div>
        <div class="col-md-3 mb-5 offset-md-6">
            <Modal />
        </div>
        <div class="col-md-12">
            <Tabs />
        </div>
    </div>
</template>

<script>
import Tabs from "./components/Tabs.vue";
import Search from "./components/Search.vue";
import Modal from "./components/Modal.vue";
export default {
    components: {
        Search,
        Tabs,
        Modal,
    },
};
</script>
