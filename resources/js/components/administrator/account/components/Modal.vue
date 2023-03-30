<template>
    <div>
        <v-btn class="ma-2" block color="black" @click="openModal()">
            CREATE ACCOUNT
        </v-btn>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">CREATE ACCOUNT</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <p
                            class="text-danger font-weight-bold"
                            v-if="error !== null"
                        >
                            {{ error }}
                        </p>

                        <v-select
                            v-model="unitName"
                            :items="unitItem"
                            :rules="unitNameRules"
                            label="Unit Name"
                            required
                            variant="outlined"
                            density="compact"
                            v-if="jobPosition !== 'Administrator'"
                        ></v-select>

                        <v-select
                            v-model="jobPosition"
                            :items="[
                                'Administrator',
                                'Ticket Office Manager',
                                'Ticket Office Staff',
                                'Inventory Supervisor',
                            ]"
                            :rules="jobPositionRules"
                            label="Job Position"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-select>
                        <v-text-field
                            v-model="fullName"
                            :rules="fullNameRules"
                            label="Fullname"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="username"
                            :rules="usernameRules"
                            label="Username"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="password"
                            :rules="passwordRules"
                            label="Password"
                            required
                            variant="outlined"
                            density="compact"
                            :type="show1 ? 'text' : 'password'"
                            :append-inner-icon="
                                show1 ? 'mdi-eye' : 'mdi-eye-off'
                            "
                            @click:appendInner="show1 = !show1"
                        ></v-text-field>

                        <div class="col-md-6 mt-5 offset-md-6 mb-3">
                            <v-btn color="error" class="mr-4" @click="reset">
                                CANCEL
                            </v-btn>

                            <v-btn
                                color="black"
                                :loading="loading"
                                class="mr-4"
                                @click="validate"
                            >
                                SUBMIT
                            </v-btn>
                        </div>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import moment from "moment";
export default {
    props: ["editId"],
    components: { Datepicker },
    data: () => ({
        loading: false,
        show1: false,
        dialog: false,
        unitItem: [],
        unitName: null,
        unitNameRules: [(v) => !!v || "Unit Name is required"],
        jobPosition: null,
        jobPositionRules: [(v) => !!v || "Job Position is required"],
        fullName: "",
        fullNameRules: [(v) => !!v || "Fullname is required"],
        username: "",
        usernameRules: [(v) => !!v || "Username is required"],
        password: "",
        passwordRules: [(v) => !!v || "Password is required"],
    }),
    mounted() {
        axios
            .get("/api/get_all_units")
            .then((res) => {
                this.unitItem = res.data.status.map(
                    (a) => a.ee_client_unit_name
                );
            })
            .catch((err) => {});
    },
    methods: {
        openModal() {
            this.dialog = true;
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                axios
                    .post("/add_user_account", {
                        name: this.fullName,
                        email: this.username,
                        job: this.jobPosition,
                        unitName: this.unitName,
                        password: this.password,
                    })
                    .then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "User successfully added!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.$router.push({
                            path: "/administrator/accounts/loading",
                        });
                        this.loading = true;
                    })
                    .catch((err) => {
                        this.$swal({
                            icon: "error",
                            title: "Error!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.loading = true;
                    });
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
    },
};
</script>
