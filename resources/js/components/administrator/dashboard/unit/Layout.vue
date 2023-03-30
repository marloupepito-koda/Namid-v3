<template>
    <div class="row">
        <div className="col-md-12">
            <Breadcrumbs />
        </div>
        <div className="col-md-12">
            <div>
                <Tabs />
            </div>
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
    data: () => ({
        tab: null,
    }),
    mounted() {},
};
</script>
