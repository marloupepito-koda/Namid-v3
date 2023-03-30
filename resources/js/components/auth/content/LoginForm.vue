<template>
    <div class="p-3 mt-5 mb-5">
        <v-img src="/images/namidlogo.png" width="100%" class="mb-5" />
        <v-form ref="form" v-model="valid" lazy-validation @submit="validate">
            <v-text-field
                v-model="username"
                :counter="20"
                :rules="usernameRules"
                label="Username"
                required
                variant="outlined"
                class="mb-2 p-0"
                density="compact"
            ></v-text-field>

            <v-text-field
                v-model="password"
                :rules="passwordRules"
                label="Password"
                required
                density="compact"
                :type="show ? 'text' : 'password'"
                variant="outlined"
                class="mb-2"
                @click:append-inner="show = !show"
                :append-inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            ></v-text-field>

            <v-btn
                type="submit"
                color="black"
                :loading="loading"
                block
                class="mb-4"
                @click="validate"
            >
                LOGIN
            </v-btn>
        </v-form>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data: () => ({
        alert: false,
        messageAlert: false,
        loading: false,
        show: false,
        valid: true,
        username: "",
        usernameRules: [(v) => !!v || "username is required"],
        password: "",
        passwordRules: [(v) => !!v || "Password is required"],
    }),
    mounted() {
        //  console.log(JSON.parse(localStorage.getItem("access")));
    },
    methods: {
        async validate(e) {
            e.preventDefault();
            this.$refs.form.validate();
            const { valid } = await this.$refs.form.validate();
            this.loading = true;
            if (valid) {
                axios
                    .post("/ee_login_users", {
                        email: this.username,
                        password: this.password,
                    })
                    .then((result) => {
                        axios
                            .get("/user")
                            .then((res) => {
                                if (res.data.usertype === "Administrator") {
                                    localStorage.setItem(
                                        "login_unit_id",
                                        res.data.unit_id
                                    );
                                    localStorage.setItem(
                                        "usertype",
                                        res.data.usertype
                                    );
                                    this.loading = false;
                                    this.$router.push({
                                        path: "/administrator/dashboard",
                                    });
                                    this.alert = "success";
                                    this.messageAlert = "Success!";
                                } else {
                                    if (res.data.access1 === "On") {
                                        this.loading = false;
                                        this.$router.push({
                                            path:
                                                "/unit/branch/" +
                                                res.data.unit_id +
                                                "/" +
                                                res.data.unit_name.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/event_list",
                                        });
                                        localStorage.setItem(
                                            "access",
                                            JSON.stringify(res.data)
                                        );
                                        this.$swal({
                                            icon: "success",
                                            title: "Success!",
                                            showConfirmButton: false,
                                            timer: 1500,
                                        });
                                    } else {
                                        this.$swal({
                                            icon: "error",
                                            title: "You don't have permission to access!",
                                            showConfirmButton: false,
                                            timer: 1500,
                                        });
                                        this.loading = false;
                                    }
                                }
                            })
                            .catch((err) => {
                                this.loading = false;
                            });
                        //  this.$router.push({ path: '/dashboard'})
                    })
                    .catch((err) => {
                        this.loading = false;
                        this.alert = "error";
                        this.messageAlert =
                            err.response.data.message.charAt(0).toUpperCase() +
                            err.response.data.message.slice(1);
                    });
            } else {
                this.loading = false;
            }
        },
        reset() {
            this.$refs.form.reset();
        },
        resetValidation() {
            this.$refs.form.resetValidation();
        },
    },
};
</script>
