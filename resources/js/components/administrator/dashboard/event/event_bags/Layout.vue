<template>
    <div class="row">
        <div class="col-md-4 offset-md-5">
            <Search />
        </div>
        <div class="col-md-3">
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
