<template>
    <div class="text-center">
        <v-dialog v-model="dialog" width="500">
            <template v-slot:activator="{ props }">
                <v-btn color="black" block v-bind="props"> CREATE UNIT </v-btn>
            </template>

            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">CREATE UNIT</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="campanyFullName"
                            :counter="10"
                            :rules="campanyFullNameRules"
                            label="Full Company Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="campanyShortName"
                            :rules="campanyShortNameRules"
                            label="Short Company Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="campanyCode"
                            :items="items"
                            :rules="campanyCodeRules"
                            label="Company Code"
                            required
                            variant="outlined"
                            density="compact"
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
import moment from "moment";
export default {
    data: () => ({
        dialog: false,
        valid: true,
        campanyFullName: "",
        loading: false,
        campanyFullNameRules: [(v) => !!v || "Full Company Name is required"],
        campanyShortName: "",
        campanyShortNameRules: [(v) => !!v || "Short Company Name is required"],
        campanyCode: "",
        campanyCodeRules: [(v) => !!v || "Short Company Name is required"],
    }),

    methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.loading = true;
                axios
                    .post("/adding_unit", {
                        unit: this.campanyFullName,
                        companyshortname: this.campanyShortName,
                        companycode: this.campanyCode,
                        date: moment(new Date()).format("LL"),
                    })
                    .then((res) => {
                        this.dialog = false;
                        this.loading = false;
                        if (res.data.status === "exist1") {
                            this.$swal({
                                icon: "error",
                                title: "Unit already exists",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        } else {
                            this.$router.push({
                                path: "/administrator/loading",
                            });
                            this.$swal({
                                icon: "success",
                                title: "Unit is added!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.units = result.data.status;
                        }
                    })
                    .catch((err) => {
                        this.dialog = false;
                        this.loading = false;
                        // this.$swal({
                        //     icon: "error",
                        //     title: "Invalid Short Campany Name format.",
                        //     showConfirmButton: false,
                        //     timer: 1500,
                        // });
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
