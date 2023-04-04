<template>
    <div class="row">
        <!-- <div class="col-md-3 offset-md-9"><Modal /></div> -->
        <div class="col-md-12">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import Modal from "./components/Modal.vue";
export default {
    components: {
        Modal,
    },
};
</script>
