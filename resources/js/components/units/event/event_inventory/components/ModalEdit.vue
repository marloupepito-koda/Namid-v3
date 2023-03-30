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
                        <span class="text-h5">Edit Ticket Price</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-12">
                                ATP:
                                <b>{{
                                    getData.ticket_price / getData.ticket_count
                                }}</b>
                            </div>
                            <div class="col-md-12">
                                Total Ticket: <b>{{ getData.const }}</b>
                            </div>
                            <div class="col-md-12 mb-2">
                                Ticket Counting:
                                <b>{{
                                    getData.ticket_end -
                                    getData.ticket_start +
                                    1
                                }}</b>
                            </div>
                            <div class="col-md-12 mb-5">
                                <div
                                    class="col-md-12 text-danger"
                                    v-if="
                                        getData.ticket_end -
                                            getData.ticket_start +
                                            1 !==
                                        parseInt(getData.const)
                                    "
                                >
                                    The total ticket and ticket counting must be
                                    identical.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="getData.ticket_number"
                                    :rules="ticketNameRules"
                                    label="Ticket Name"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="getData.ticket_price"
                                    :rules="ticketPriceRules"
                                    label="Ticket Price"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="getData.ticket_start"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="getData.ticket_end"
                                    :rules="endRules"
                                    label="Ending #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                        </div>

                        <div class="col-md-6 mt-5 offset-md-6 mb-3">
                            <v-btn color="error" class="mr-4" @click="reset">
                                CANCEL
                            </v-btn>

                            <v-btn
                                color="black"
                                class="mr-4"
                                :loading="loading"
                                @click="validate"
                                :disabled="
                                    parseInt(
                                        getData.ticket_end -
                                            getData.ticket_start +
                                            1
                                    ) === parseInt(getData.const)
                                        ? false
                                        : true
                                "
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
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        ticketName: "",
        ticketNameRules: [(v) => !!v || "Ticket Name is required"],
        ticketPrice: "",
        ticketPriceRules: [(v) => !!v || "Ticket Price Name is required"],
        start: "",
        startRules: [(v) => !!v || "Starting # is required"],
        end: "",
        endRules: [(v) => !!v || "Ending # is required"],
        getData: [],
        priced: "",
    }),
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");
            this.eventId = this.$route.query.event_id;
        },

        logs(event) {
            axios
                .post("/add_logs", {
                    unit_id: this.unitId,
                    event_id: this.eventId,
                    ticket_id: this.editId,
                    descriptions: event,
                })
                .then((res) => {});
        },
        openModal() {
            axios
                .post("/show_each_ticket_in_event", {
                    id: this.editId,
                })
                .then((res) => {
                    this.dialog = true;
                    console.log(this.unitName.replace(/ /g, "_"));
                    this.getData = res.data.status;
                    this.priced = res.data.status.ticket_price;
                });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                axios
                    .post("/edit_ticket_in_events", {
                        id: this.editId,
                        editStart: this.getData.ticket_start,
                        editEnd: this.getData.ticket_end,
                        ticketPrice: this.getData.ticket_price,
                        editCount: this.getData.ticket_count,
                    })
                    .then((res) => {
                        this.loading = false;
                        this.dialog = false;
                        const name = localStorage.getItem("name");
                        this.logs(
                            name +
                                " Edit the Ticket Price from " +
                                this.priced +
                                " to " +
                                this.getData.ticket_price
                        );
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
                                    "event_inventory",
                                    String(this.eventId),
                                ],
                            },
                        });
                        this.$swal({
                            icon: "success",
                            title: "Success!",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        this.tickets = res.data.status;
                    });
            }
        },
        reset() {
            // this.$refs.form.reset();
            this.dialog = false;
            this.dateRules = null;
            this.error = null;
        },
    },
};
</script>
