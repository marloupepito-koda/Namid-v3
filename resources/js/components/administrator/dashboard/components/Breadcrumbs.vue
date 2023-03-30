<template>
    <div>
        <v-breadcrumbs :items="items">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
    </div>
</template>

<script>
export default {
    data: () => ({
        items: [
            {
                title: "Dashboard",
                disabled: false,
                to: "/administrator/dashboard",
            },
            {
                title: "",
                disabled: true,
            },
        ],
    }),
};
</script>
