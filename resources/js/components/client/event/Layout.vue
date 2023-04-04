<template>
    <div>
        <v-navigation-drawer
            v-model="drawer"
            :rail="rail"
            permanent
            app
            @click="rail = false"
            width="235"
        >
            <v-list-item :title="''" nav>
                <template v-slot:append>
                    <img src="/images/namidlogo.png" class="mr-2 mt-3" />
                </template>
            </v-list-item>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item
                    :prepend-icon="i.icon"
                    v-for="i in items"
                    @click="goToNexT(i.id, i.active)"
                    :title="i.title"
                    :value="i.title"
                    :active="active === i.active ? true : false"
                ></v-list-item>
            </v-list>

            <template v-slot:append>
                <div class="pa-2">
                    <v-list nav>
                        <v-btn
                            block
                            class="bg-black"
                            color="black"
                            value="a"
                            @click="logoutUser"
                        >
                            <div v-if="rail === true">
                                <v-icon>mdi-logout</v-icon>
                            </div>

                            <div v-else><v-icon>mdi-logout</v-icon> LOGOUT</div>
                        </v-btn>
                    </v-list>
                </div>
            </template>
        </v-navigation-drawer>
        <v-content>
            <v-app-bar color="black" density="compact">
                <template v-slot:prepend>
                    <v-btn
                        icon="mdi-format-align-left"
                        @click.stop="rail = !rail"
                    ></v-btn>
                </template>

                <v-app-bar-title>{{ name }}</v-app-bar-title>

                <template v-slot:append>
                    {{ timezone }}
                    <v-btn @click="logoutUser" icon="mdi-logout"></v-btn>
                </template>
            </v-app-bar>
            <v-main>
                <div class="row">
                    <div class="col-md-12">
                        <Breadcrumbs />
                    </div>
                    <div class="col-md-12">
                        <router-view></router-view>
                    </div>
                </div>
            </v-main>
        </v-content>
    </div>
</template>
<script>
import Breadcrumbs from "./components/Breadcrumbs.vue";
import moment from "moment";
export default {
    data() {
        return {
            timezone: moment().format("MMMM D, YYYY, h:mm:ss a"),
            drawer: true,
            active: "",
            name: "",
            items: [
                {
                    id: 1,
                    title: "Event Inventory",
                    icon: "mdi-storefront-outline",
                    active: "event_inventory",
                },
                {
                    id: 2,
                    title: "Event Bags",
                    icon: "mdi-bag-personal-tag-outline",
                    active: "event_bags/all_bags",
                },
                {
                    id: 3,
                    title: "Returned Bags",
                    icon: "mdi-ticket-confirmation-outline",
                    active: "returned_bags",
                },
                {
                    id: 4,
                    title: "Event Logs",
                    icon: "mdi-list-box-outline",
                    active: "logs",
                },
                {
                    id: 5,
                    title: "Event History",
                    icon: "mdi-history",
                    active: "event_history",
                },
            ],
            rail: false,
        };
    },
    components: {
        Breadcrumbs,
    },
    async mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.active =
            this.$route.path.split("/")[5] === "event_bags"
                ? "event_bags/all_bags"
                : this.$route.path.split("/")[5];
        await axios.get("/user").then((res) => {
            this.name = res.data.name;
            localStorage.setItem("name", res.data.name);
        });
        this.showLocaleTime();
    },
    methods: {
        showLocaleTime: function () {
            var time = this;
            setInterval(function () {
                time.timezone = moment().format("MMMM D, YYYY, h:mm:ss a");
            }, 1000);
        },
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
                    this.$router.push({ path: "/administrator/logout" });
                }
            });
        },
        goToNexT(id, active) {
            this.active = active;
            this.$router.push({
                path:
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/" +
                    active,
            });
        },
    },
};
</script>
