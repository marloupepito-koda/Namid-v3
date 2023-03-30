<template>
    <div>
        <v-breadcrumbs :items="items" @click="nextPage">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script>
export default {
    mounted() {
        this.items = [
            // {
            //     title: "Dashboard",
            //     disabled: false,
            //     id: 1,
            //     href: "javascript:;",
            // },
            {
                title: this.$route.path.split("/")[4].replace(/_/g, " "),
                disabled: false,
                id: 2,
            },
        ];
    },
    methods: {
        nextPage(e) {
            if (e.target.id === "1") {
                this.$router.push({ path: "/unit/branch/" });
            }
        },
    },
    data: () => ({
        items: [],
    }),
};
</script>
