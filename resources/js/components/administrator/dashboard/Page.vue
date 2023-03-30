<template>
    <div>
        <LayoutVue />
    </div>
</template>

<script>
import LayoutVue from "./Layout.vue";
export default {
    components: {
        LayoutVue,
    },
    data() {
        return {
            dialog: false,
        };
    },
};
</script>
