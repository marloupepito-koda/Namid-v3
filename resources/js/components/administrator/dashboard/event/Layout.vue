<template>
    <div class="row">
        <div class="col-md-12">
            <Breadcrumbs />
        </div>
        <div class="col-md-12">
            <Tabs />
        </div>
    </div>
</template>
<script>
import Breadcrumbs from "./components/Breadcrumbs.vue";
import Tabs from "./components/Tabs.vue";
export default {
    components: {
        Breadcrumbs,
        Tabs,
    },
};
</script>
