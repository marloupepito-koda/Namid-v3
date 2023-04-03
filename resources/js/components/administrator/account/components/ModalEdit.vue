<template>
    <div>
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="black"
            @click="openModal(editId)"
        >
            <v-icon size="large"> mdi-account-edit-outline </v-icon>
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
                                class="mr-4"
                                :loading="loading"
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
                this.unitItem = res.data.status.map((a) => a.units_name);
            })
            .catch((err) => {});
    },
    methods: {
        openModal() {
            this.dialog = true;
            axios
                .get("/api/get_every_users/" + this.editId)
                .then((res) => {
                    this.fullName = res.data.status.name;
                    this.username = res.data.status.email;
                    this.jobPosition = res.data.status.usertype;
                    this.unitName = res.data.status.unit_name;
                })
                .then((res) => {});
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                axios
                    .put("/edit_every_user", {
                        id: this.editId,
                        name: this.fullName,
                        email: this.username,
                        job: this.jobPosition,
                        unitName: this.unitName,
                        password: this.password,
                    })
                    .then((res) => {
                        this.dialog = false;
                        this.$swal({
                            icon: "success",
                            title: "Your work has been saved",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.$router.push({
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.loading = false;
                    })
                    .catch((err) => {
                        this.loading = false;
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
