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
                <router-view></router-view>
            </v-main>
        </v-content>
    </div>
</template>

<script>
import moment from "moment";
export default {
    components: {},
    data() {
        return {
            timezone: moment().format("MMMM D, YYYY, h:mm:ss a"),
            drawer: true,
            active: "",
            name: "",
            items: [
                {
                    id: 1,
                    title: "Unit Dashboard",
                    icon: "mdi-home-city",
                    active: "dashboard",
                },
                {
                    id: 2,
                    title: "Spot Transactions",
                    icon: "mdi-briefcase-arrow-left-right-outline",
                    active: "transaction",
                },
                {
                    id: 3,
                    title: "Committee Settlement",
                    icon: "mdi-receipt-text-check-outline",
                    active: "settlement",
                },
                {
                    id: 4,
                    title: "Users Account",
                    icon: "mdi-shield-account-outline",
                    active: "accounts",
                },
            ],
            rail: false,
        };
    },
    async mounted() {
        this.active = this.$route.path.split("/")[2];
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
            if (id === 1) {
                this.$router.push({ path: "/administrator/dashboard" });
            } else if (id === 2) {
                this.$router.push({ path: "/administrator/transaction" });
            } else if (id === 3) {
                this.$router.push({ path: "/administrator/settlement" });
            } else if (id === 4) {
                this.$router.push({ path: "/administrator/accounts" });
            }
        },
    },
};
</script>
