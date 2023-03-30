<template>
    <div>
        <v-content>
            <v-app-bar color="black" density="compact">
                <v-app-bar-title>
                    <img src="/images/namidlogo.png" />
                </v-app-bar-title>
                <template v-slot:append>
                    <b>{{ name }}</b>
                    <v-btn
                        icon="mdi-account-lock-open-outline"
                        @click="logoutUser"
                    ></v-btn>
                </template>
            </v-app-bar>
            <v-main>
                <div class="row">
                    <div class="col-md-3"><Download /></div>
                    <div class="col-md-3"><Upload /></div>
                </div>

                <router-view></router-view>
            </v-main>
        </v-content>
    </div>
</template>

<script>
import Download from "./components/Download.vue";
import Upload from "./components/Upload.vue";
export default {
    components: {
        Download,
        Upload,
    },
    data() {
        return {
            active: "",
            name: "",
        };
    },
    mounted() {
        this.active = this.$route.path.split("/")[2];
        axios.get("/user").then((res) => {
            this.name = res.data.name;
        });
    },
    methods: {
        logoutUser() {
            this.$swal({
                title: "You want to logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Logout",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$router.push({ path: "/unit/branch/logout" });
                }
            });
        },
    },
};
</script>
