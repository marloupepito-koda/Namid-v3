<template>
    <div class="text-center">
        <v-dialog v-model="dialog" width="500">
            <template v-slot:activator="{ props }">
                <v-btn color="black" block v-bind="props"> CREATE BAG </v-btn>
            </template>

            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">Create Bag</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="bagName"
                            :rules="bagNameRules"
                            label="Bag Name"
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
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import moment from "moment";
export default {
    components: { Datepicker },
    data: () => ({
        loading: false,
        unitId: "",
        eventId: "",
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        bagName: "",
        bagNameRules: [(v) => !!v || "Bag Name is required"],
        bagDestination: "",
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
    },
    methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const date = moment(this.date).format("LLLL");

            if (valid) {
                this.loading = true;
                axios
                    .post("/create_bag", {
                        unitid: this.unitId,
                        eventid: this.eventId,
                        bag_name: this.bagName,
                        date: date,
                    })
                    .then((res) => {
                        this.loading = false;
                        if (res.data.status === "success") {
                            this.dialog = false;
                            this.$swal({
                                icon: "success",
                                title: "Bag Added",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            this.$router.push({
                                hash: "#" + Math.floor(Math.random() * 999999),
                            });
                        } else {
                            this.dialog = false;
                            this.$swal({
                                icon: "error",
                                title: "Name of bag is exist!",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        }
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
