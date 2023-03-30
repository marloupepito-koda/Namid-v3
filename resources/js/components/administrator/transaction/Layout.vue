<template>
    <div class="row">
        <div class="col-md-12">
            <Search />
        </div>
        <div class="col-md-12">
            <View />
        </div>
    </div>
</template>
<script>
import View from "./components/View.vue";
import Search from "./components/Search.vue";
export default {
    components: {
        View,
        Search,
    },
};
</script>
