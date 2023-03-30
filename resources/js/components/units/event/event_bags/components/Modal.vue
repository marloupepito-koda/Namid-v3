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
        this.eventId =
            Array.isArray(this.$route.query.event_id) === true
                ? this.$route.query.event_id[0]
                : this.$route.query.event_id;
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[7];
    },
    methods: {
        logs(event) {
            axios
                .post("/add_logs", {
                    unit_id: this.unitId,
                    event_id: this.eventId,
                    ticket_id: "",
                    descriptions: event,
                })
                .then((res) => {});
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const date = moment(this.date).format("LLLL");

            const date2 = moment(this.$route.query.searchDate).format("LLL");
            if (valid) {
                this.loading = true;
                axios
                    .post("/add_bag_inventory", {
                        foreign_unit: this.unitId,
                        foreign_spot: this.eventId,
                        bagName: this.bagName,
                        date: date,
                    })
                    .then((res) => {
                        this.loading = false;
                        if (res.data.status === "success") {
                            const name = localStorage.getItem("name");
                            this.logs(name + " added Bag " + this.bagName);
                            const bagPath = this.$route.path.split("/")[7];
                            this.$swal({
                                icon: "success",
                                title: "Bag Added",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            this.$router.push({
                                path:
                                    "/unit/branch/" +
                                    this.unitId +
                                    "/" +
                                    this.unitName.replace(/ /g, "_") +
                                    "/" +
                                    this.eventName.replace(/ /g, "_") +
                                    "/loading",
                                query: {
                                    where: [
                                        "event_bags/" + bagPath,
                                        String(this.eventId),
                                    ],
                                },
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
