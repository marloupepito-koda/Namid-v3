<template>
    <div>
        <div class="col-md-3">
            <h3 class="ml-3"><b>Event Transaction Logs</b></h3>
        </div>
        <Timeline />
    </div>
</template>

<script>
import Timeline from "./components/Timeline.vue";

export default {
    components: {
        Timeline,
    },
};
</script>
