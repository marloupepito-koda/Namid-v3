<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            large
            color="black"
            @click="openModal(editId)"
        >
            <v-icon size="large" color="black">
                mdi-briefcase-edit-outline</v-icon
            >
        </v-btn>
        <v-dialog v-model="dialog" width="500">
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

                        <v-text-field
                            disabled
                            v-model="bagName"
                            :rules="bagNameRules"
                            label="Bag Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

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
    props: [
        "editId",
        "editSellerName",
        "editBagId",
        "editLocation",
        "editBagName",
    ],
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
        sellerName1: "",
        bagName: "",
        bagNameRules: [(v) => !!v || "Bag Name is required"],
        location: "",
        locationRules: [(v) => !!v || "Location is required"],
        bagDestination: "",
        date: "",
        bagGlobalId: "",
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
        openModal() {
            this.dialog = true;
            this.sellerName = this.editSellerName;
            this.bagGlobalId = this.editBagId;
            this.location = this.editLocation;
            this.bagName = this.editBagName;
        },
        mount() {
            this.date =
                this.$route.query.searchDate === undefined
                    ? moment(new Date()).format("LLL")
                    : moment(new Date(this.$route.query.searchDate)).format(
                          "LLL"
                      );
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const date = moment(this.date).format("LLLL");
            const time = moment().format("LTS");
            if (valid) {
                this.loading = true;
                axios
                    .post("/edit_seller_info", {
                        unitid: this.unitId,
                        eventid: this.eventId,
                        id: this.editId,
                        bagGlobalId: this.bagGlobalId,
                        editSellerName: this.sellerName,
                        editSellerName1: this.sellerName1,
                        editBagName: this.bagName,
                        editLocation: this.location,
                        date: moment(new Date()).format("LLLL"),
                    })
                    .then((result) => {
                        this.dialog = false;
                        this.loading = false;
                        if (result.data.status === "success") {
                            this.$swal({
                                icon: "success",
                                title: "Seller Saved!",
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
                                        "event_sellers/",
                                        String(this.eventId),
                                    ],
                                    searchDate: this.date,
                                },
                            });
                        } else {
                            this.loading = false;
                            this.$swal({
                                icon: "error",
                                title: "Seller name is exist!",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        }
                    })
                    .catch((err) => {
                        this.loading = false;
                        this.disabled = false;
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
