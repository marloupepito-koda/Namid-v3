<template>
    <div>
        <v-breadcrumbs class="text-capitalize" :items="items" @click="nextPage">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script>
export default {
    mounted() {
        console.log();
        this.items = [
            {
                title: "Client",
                disabled: false,
                id: 1,
                href: "javascript:;",
            },
            {
                title: JSON.parse(localStorage.getItem("access")).unitname,
                disabled: false,
                id: 2,
            },
        ];
    },
    methods: {
        nextPage(e) {
            if (e.target.id === "1") {
                this.$router.push({
                    path:
                        "/client/branch/" +
                        JSON.parse(localStorage.getItem("access")).unitid +
                        "/event_list",
                });
            }
        },
    },
    data: () => ({
        items: [],
    }),
};
</script>
