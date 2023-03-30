<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="blue"
            @click="openModal(editId)"
        >
            <v-icon size="large" color="blue">
                mdi-clipboard-edit-outline
            </v-icon>
        </v-btn>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">Edit Event</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <p
                            class="text-danger font-weight-bold"
                            v-if="error !== null"
                        >
                            {{ error }}
                        </p>
                        <Datepicker
                            class="mb-3"
                            placeholder="Select Date"
                            v-model="date"
                            :state="dateRules"
                            range
                            multi-calendars
                            input-class-name="dp-custom-input"
                        ></Datepicker>

                        <v-text-field
                            v-model="eventName"
                            :counter="10"
                            :rules="eventNameRules"
                            label="Event Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="eventAddress"
                            :rules="eventAddressRules"
                            label="Event Address"
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
    props: ["editId"],
    components: { Datepicker },
    data: () => ({
        loading: false,
        unitId: "",
        eventId: "",
        date: null,
        dateRules: null,
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        eventNameRules: [(v) => !!v || "Full Company Name is required"],
        eventAddress: "",
        eventAddressRules: [(v) => !!v || "Short Company Name is required"],
        campanyCode: "",
        campanyCodeRules: [(v) => !!v || "Short Company Name is required"],
        error: null,
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id;
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
    },
    methods: {
        openModal() {
            axios
                .post("/get_edit_event", {
                    id: this.editId,
                })
                .then((res) => {
                    this.dialog = true;

                    this.date = [
                        res.data.status.ee_events_unit_start_date,
                        res.data.status.ee_events_unit_end_date,
                    ];
                    this.eventName = res.data.status.ee_events_unit_spot;
                    this.eventAddress = res.data.status.ee_events_unit_location;
                });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (this.date === null || this.date === "") {
                this.dateRules = false;
            } else {
                this.dateRules = null;
            }

            if (valid && this.date !== null && this.date !== "") {
                this.error = null;
                this.loading = true;
                axios
                    .post("/update_event", {
                        id: this.editId,
                        start: moment(this.date[0]).format("L"),
                        end: moment(this.date[1]).format("L"),
                        spot: this.eventName,
                        location: this.eventAddress,
                    })
                    .then((res) => {
                        this.loading = false;
                        this.$swal({
                            icon: "success",
                            title: "Updated successfully!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.$router.push({
                            path:
                                "/unit/branch/" +
                                this.unitId +
                                "/" +
                                this.unitName.replace(/ /g, "_") +
                                "/loading",
                            query: { where: "event_list" },
                        });
                    })
                    .catch((err) => {
                        this.loading = false;
                    });
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
            this.dateRules = null;
            this.error = null;
        },
    },
};
</script>
