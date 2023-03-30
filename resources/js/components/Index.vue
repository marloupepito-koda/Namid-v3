<template>
    <v-app>
        <v-snackbar
            v-if="onLine === true"
            location="top right"
            :timeout="5000"
            color="success"
            v-model="snackbar"
            style="margin-top: 45px"
        >
            <v-icon end icon="mdi-wifi-check"></v-icon>
            Online Internet Connection
        </v-snackbar>
        <v-snackbar
            v-else
            location="top right"
            :timeout="5000"
            color="danger"
            v-model="snackbar"
            style="margin-top: 45px"
        >
            <v-icon end icon="mdi-wifi-off"></v-icon>
            Offline Internet Connection
        </v-snackbar>
        <router-view></router-view>
    </v-app>
</template>

<script>
import { useRoute } from "vue-router";
import axios from "axios";
export default {
    data() {
        return {
            snackbar: false,
            onLine: navigator.onLine,
            showBackOnline: false,
        };
    },

    methods: {
        // showLocaleTime: function () {
        //     setInterval(function () {

        //     }, 1000);
        // },
        updateOnlineStatus(e) {
            const CACHE_NAME = "static_cache";
            const STATIC_ASSETS = ["index.php"];

            const { type } = e;
            this.onLine = type === "online";
            this.snackbar = true;
            localStorage.setItem("internet", type);
            if (type === "online") {
                //localStorage.setItem("names", JSON.stringify(names));
            }
        },
    },
    watch: {
        async $route(to, from) {
            const urls = to.fullPath;
            await caches.open("static_cache").then((cache) => {
                cache.addAll([urls]).then(() => {
                    // console.log("Data cached ");
                });
            });
        },
        onLine(v) {
            if (v) {
                this.showBackOnline = true;

                setTimeout(() => {
                    this.showBackOnline = false;
                }, 1000);
            }
        },
    },
    mounted() {
        this.showBackOnline = true;

        window.addEventListener("online", this.updateOnlineStatus);
        window.addEventListener("offline", this.updateOnlineStatus);

        axios
            .get("/user")
            .then((res) => {})
            .catch((err) => {
                if (
                    window.location.pathname.split("/")[1] !== "auth" ||
                    window.location.pathname.split("/")[1] !== ""
                ) {
                    this.$router.push({ path: "/auth" });
                }
            });
    },
    beforeDestroy() {
        window.removeEventListener("online", this.updateOnlineStatus);
        window.removeEventListener("offline", this.updateOnlineStatus);
    },
};
</script>
