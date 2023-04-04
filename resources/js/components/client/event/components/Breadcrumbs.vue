<template>
    <div class="text-capitalize">
        <v-breadcrumbs :items="items" @click="clickMe">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script>
export default {
    mounted() {
        this.mount();
    },
    data: () => ({
        unitId: "",
        unitName: "",
        eventName: "",
        eventLocation: "",
        items: [],
        eventId: "",
    }),
    methods: {
        clickMe(e) {
            this.$router.push({ path: e.target.id });
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];
            axios
                .get(
                    "/api/get_unit_and_events/" +
                        this.unitId +
                        "/" +
                        this.eventId
                )
                .then((res) => {
                    this.items = [
                        {
                            title: "Event List",
                            disabled: false,
                            id: "/client/branch/" + this.unitId + "/event_list",
                            href: "javascript:;",
                        },
                        {
                            title: res.data.unit.units_name,
                            disabled: false,
                            id: "/client/branch/" + this.unitId + "/event_list",
                            href: "javascript:;",
                        },
                        {
                            title: res.data.event.events_name,
                            disabled: false,
                            href: "javascript:;",
                        },
                    ];
                });
        },
    },
};
</script>
