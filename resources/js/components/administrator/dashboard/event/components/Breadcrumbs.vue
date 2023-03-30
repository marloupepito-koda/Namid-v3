<template>
    <!-- <div class="text-capitalize">
        <v-breadcrumbs :items="items" @click="clickMe">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
    </div> -->
</template>

<script>
// export default {
//     mounted() {
//         this.mount();
//     },
//     data: () => ({
//         unitId: "",
//         unitName: "",
//         eventName: "",
//         eventLocation: "",
//         items: [],
//     }),
//     methods: {
//         clickMe(e) {
//             this.$router.push({ path: e.target.id });
//         },
//         mount() {
//             this.unitId = this.$route.path.split("/")[3];
//             this.unitName = this.$route.path.split("/")[4];
//             this.eventName = this.$route.path.split("/")[5];

//             this.eventLocation = this.$route.path
//                 .split("/")[6]
//                 .replace(/_/g, " ");
//             this.items = [
//                 {
//                     title: "Dashboard",
//                     disabled: false,
//                     id: "/administrator/dashboard",
//                     href: "javascript:;",
//                 },
//                 {
//                     title: this.$route.path.split("/")[4].replace(/_/g, " "),
//                     disabled: false,
//                     id:
//                         "/administrator/dashboard/" +
//                         this.unitId +
//                         "/" +
//                         this.unitName +
//                         "/event_list",
//                     href: "javascript:;",
//                 },
//                 {
//                     title: this.$route.path.split("/")[5].replace(/_/g, " "),
//                     disabled: false,
//                     id:
//                         "/administrator/dashboard/" +
//                         this.unitId +
//                         "/" +
//                         this.unitName +
//                         "/event_list",
//                     href: "javascript:;",
//                 },
//             ];
//         },
//     },
// };
</script>
