<template>
    <div class="text-center">
        <v-dialog v-model="dialog" width="500">
            <template v-slot:activator="{ props }">
                <v-btn color="black" block v-bind="props"> ADD A SELLER </v-btn>
            </template>

            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">ADD A SELLER</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="sellerName"
                            :rules="sellerNameRules"
                            label="Seller Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-select
                            :items="items"
                            item-title="value"
                            label="Bag Name"
                            variant="outlined"
                            density="compact"
                            required
                            v-model="bagName"
                            :rules="bagNameRules"
                        ></v-select>

                        <v-text-field
                            v-model="location"
                            :rules="locationRules"
                            label="Location"
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
    components: { Datepicker },
    data: () => ({
        loading: false,
        items: [],
        unitId: "",
        eventId: "",
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        sellerName: "",
        sellerNameRules: [(v) => !!v || "Seller Name is required"],
        bagName: "",
        bagNameRules: [(v) => !!v || "Bag Name is required"],
        location: "",
        locationRules: [(v) => !!v || "Location is required"],
        bagDestination: "",
        date: "",
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id;
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[7];
        this.mount();
    },
    methods: {
        mount() {
            this.date =
                this.$route.query.searchDate === undefined
                    ? moment(new Date()).format("LLL")
                    : moment(new Date(this.$route.query.searchDate)).format(
                          "LLL"
                      );
            axios
                .post("/get_available_bag", {
                    date: this.date,
                    foreign_unit: this.unitId,
                    foreign_spot: this.eventId,
                })
                .then((res) => {
                    this.items = res.data.status.map((res) => ({
                        value: res.bag_name,
                    }));
                });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const date = moment(this.date).format("LLLL");
            const time = moment().format("LTS");
            if (valid) {
                this.loading = true;
                axios
                    .post("/add_a_seller2", {
                        foreign_unit: this.unitId,
                        foreign_spot: this.eventId,
                        location: this.location,
                        seller: this.sellerName,
                        bagname: this.bagName,
                        date: date,
                        time: time,
                    })
                    .then((res) => {
                        this.loading = false;
                        if (res.data.success === "success") {
                            this.dialog = false;
                            this.$swal({
                                icon: "success",
                                title: "Seller Added!",
                                showConfirmButton: false,
                                timer: 1000,
                            });

                            this.$router.push({
                                path:
                                    "/administrator/dashboard/" +
                                    this.unitId +
                                    "/" +
                                    this.unitName.replace(/ /g, "_") +
                                    "/" +
                                    this.eventName.replace(/ /g, "_") +
                                    "/loading",
                                query: {
                                    where: [
                                        "event_sellers",
                                        String(this.eventId),
                                    ],
                                },
                            });
                        } else {
                            this.loading = false;
                            this.dialog = false;
                            this.$swal({
                                icon: "error",
                                title: res.data.status,
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
