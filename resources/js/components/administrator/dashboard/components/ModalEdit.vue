<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="blue"
            @click="openModal()"
        >
            <v-icon size="large" color="blue">
                mdi-clipboard-edit-outline
            </v-icon>
        </v-btn>
        <v-dialog v-model="dialog" width="500">
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
                                :loading="loading"
                                color="black"
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
    props: ["editId"],
    data: () => ({
        loading: false,
        dialog: false,
        valid: true,
        campanyFullName: "",
        campanyFullNameRules: [(v) => !!v || "Full Company Name is required"],
        campanyShortName: "",
        campanyShortNameRules: [(v) => !!v || "Short Company Name is required"],
        campanyCode: "",
        campanyCodeRules: [(v) => !!v || "Short Company Name is required"],
    }),

    methods: {
        openModal() {
            this.dialog = true;
            this.campanyFullName = this.editId[1];
            this.campanyShortName = this.editId[2];
            this.campanyCode = this.editId[3];
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                axios
                    .put("/edit_unit", {
                        id: this.editId[0], //
                        units_name: this.campanyFullName,
                        units_fullname: this.campanyFullName,
                        units_shortname: this.campanyShortName,
                        units_code: this.campanyCode,
                    })
                    .then((res) => {
                        this.dialog = false;
                        this.loading = false;
                        this.$swal({
                            icon: "success",
                            title: "Update success!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.$router.push({
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                    })
                    .catch((err) => {
                        this.dialog = false;
                        this.loading = false;
                        this.$swal({
                            icon: "error",
                            title: "error!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
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
