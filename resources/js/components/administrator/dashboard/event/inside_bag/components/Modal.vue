<template>
    <div class="text-center">
        <v-btn color="black" @click="dialog = true">
            ADD TICKET IN A BAG
        </v-btn>
        <v-dialog persistent v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5 text-uppercase"
                            >ADD TICKET IN A BAG</span
                        >
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-6">
                                <select
                                    @change="searchTicket"
                                    class="form-select"
                                >
                                    <option selected disabled>
                                        Select Tickets
                                    </option>
                                    <option
                                        v-for="i in itemsList"
                                        :value="[i.id, i.ticket_type]"
                                        :class="
                                            i.status === '0' ? 'd-none' : ''
                                        "
                                    >
                                        {{ i.ticket_type }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="price"
                                    :rules="priceRules"
                                    label="Ticket Price"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="start"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="end"
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
                                :disabled="
                                    parseInt(start) > parseInt(end) ||
                                    parseInt(end) > parseInt(end2) ||
                                    parseInt(start) < parseInt(start2)
                                "
                                color="black"
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
import axios from "axios";
export default {
    data: () => ({
        loading: false,
        unitId: "",
        eventId: "",
        ticketId: "",
        dialog: false,
        ticket_name: "",
        ticket_nameRules: [(v) => !!v || "Ticket Name is required"],
        start: "",
        startRules: [(v) => !!v || "starting # is required"],
        end: "",
        endRules: [(v) => !!v || "Ending # is required"],
        price: "",
        priceRules: [(v) => !!v || "Price is required"],
        itemsList: [],
        count: "",
        sellers: "",
        start2: "",
        end2: "",
    }),
    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.mount();
            }
        );
    },
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];
            this.bagId = this.$route.path.split("/")[8];

            this.sellers =
                this.$route.query.seller === null
                    ? "noseller"
                    : this.$route.query.seller;
            axios
                .get(
                    "/api/get_ticket_inventory/" +
                        this.unitId +
                        "/" +
                        this.eventId
                )
                .then((res) => {
                    this.itemsList = res.data.status;
                });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const data = {
                ticketid: this.ticketId,
                start: this.start,
                end: this.end,
                price: this.price,
                date: moment().format("LLL"),
                unitid: this.unitId,
                bagid: this.bagId,
                eventid: this.eventId,
                quantity: parseInt(this.end) - parseInt(this.start) + 1,
                status: "Unsold",
            };
            if (valid) {
                axios
                    .post("/add_ticket_in_bag", data)
                    .then((res) => {
                        this.dialog = false;
                        this.price = "";
                        this.start = "";
                        this.end = "";
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                     //   alert(res.data.status)
                        this.$swal({
                            icon: "success",
                            title: "Tickets added in a bag.",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    })
                    .catch((err) => {});
            }
        },
        reset() {
            this.dialog = false;
            this.price = "";
            this.start = "";
            this.end = "";
            this.start2 = "";
            this.end2 = "";
        },
        searchTicket(e) {
            this.ticketId = e.target.value.split(",")[0];
            this.ticket_name = e.target.value.split(",")[1];
            axios
                .get("/api/get_each_ticket_in_event_inventory/" + this.ticketId)
                .then((res) => {
                    this.ticketId = res.data.status.id;
                    this.ticket_name = res.data.status.ticket_name;
                    this.start = res.data.status.start;
                    this.end = res.data.status.end;
                    this.start2 = res.data.status.start;
                    this.end2 = res.data.status.end;
                    this.price = res.data.status.price;
                    this.count = res.data.status.count;
                });
        },
    },
};
</script>
