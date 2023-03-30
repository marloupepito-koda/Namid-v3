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
                        <span class="text-h5">Edit Bag</span>
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
                                SAVE
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
    props: ["editId", "bag"],
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
        this.eventId = this.$route.query.event_id;
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[7];
    },
    methods: {
        openModal() {
            this.dialog = true;
            this.bagName = this.bag;
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const date = moment(this.date).format("LLL");
            if (valid) {
                this.loading = true;
                axios
                    .post("/edit_bag_name", {
                        id: this.editId,
                        bagname: this.bagName,
                    })
                    .then((result) => {
                        this.loading = false;
                        if (result.data.status === "success") {
                            this.$swal({
                                icon: "success",
                                title: "saved",
                                showConfirmButton: false,
                                timer: 1500,
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
                                        "event_bags/" + this.bagDestination,
                                        String(this.eventId),
                                    ],
                                },
                            });
                        } else if (result.data.status === "done") {
                            this.dialog = false;
                            this.$swal({
                                icon: "success",
                                title: "saved",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        } else {
                            this.dialog = false;
                            this.$swal({
                                icon: "error",
                                title: "Bag is already exist!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        }
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
