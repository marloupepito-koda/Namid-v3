<template>
    <div class="row">
        <div
            :class="
                access6 === 'On'
                    ? 'col-md-4 offset-md-5'
                    : 'col-md-4 offset-md-8'
            "
        >
            <Search />
        </div>
        <div class="col-md-3" v-if="access6 === 'On'">
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
    data: () => ({
        access6: "",
    }),
    mounted() {
        this.access6 = JSON.parse(localStorage.getItem("access")).access6;
    },
};
</script>
