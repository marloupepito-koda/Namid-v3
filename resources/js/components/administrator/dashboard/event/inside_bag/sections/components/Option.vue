<template>
    <div class="text-center">
        <v-btn
            size="small"
            block
            variant="outlined"
            color="black"
            @click="openModal"
            :disabled="soldDate"
        >
            <!-- :disabled="
                sellerName === null || sellerName === undefined
                    ? true
                    : soldDate
            " -->
            <v-icon large color="black"> mdi-arrow-left-top-bold</v-icon>
        </v-btn>
        <v-dialog persistent v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">TRANSFER UNSOLD TICKETS</span>
                    </v-card-title>
                    <b v-if="bagList === ''" class="text-danger">
                        Please select a bag
                    </b>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div
                                class="text-danger"
                                v-if="
                                    this.end - this.start + 1 !==
                                    this.end2 - this.start2 + 1
                                "
                            >
                                The quantity of ticket must be
                                {{ this.end2 - this.start2 + 1 }}
                            </div>
                            <div class="col-md-12 mb-5">
                                <select
                                    class="form-select"
                                    @change="searchTicket"
                                >
                                    <option
                                        :value="[i.value, i.id]"
                                        v-for="i in items"
                                        :key="i.id"
                                        :disabled="i.disable"
                                    >
                                        {{ i.value }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <v-text-field
                                    v-model="ticketName"
                                    :rules="ticketNameRules"
                                    disabled
                                    label="Ticket Type"
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
                                color="black"
                                class="mr-4"
                                @click="validate"
                                :loading="loading"
                                :disabled="
                                    parseInt(end) < parseInt(start)
                                        ? true
                                        : this.end - this.start + 1 !==
                                          this.end2 - this.start2 + 1
                                        ? true
                                        : false
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
    props: ["editId", "soldDate", "unitList"],
    components: { Datepicker },
    data: () => ({
        id: "",
        bagList: "",
        unitId: "",
        loading: false,
        eventId: "",
        date: moment().format("LLL"),
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        start: "",
        startRules: [(v) => !!v || "Starting # is required"],
        ticketName: "",
        ticketNameRules: [(v) => !!v || "Ticket Name is required"],
        end: "",
        end2: "",
        start2: "",
        endRules: [(v) => !!v || "Ending # is required"],
        notify: "",
        ticketName: "",
        bags: "",
        bagName: "",
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id[0];
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[7];
        this.bagId = this.$route.query.event_id[1];
        this.sellerName = this.$route.query.event_id[2];
        this.bagName = this.$route.query.event_id[3];

        axios
            .post("/get_available_bag2", {
                foreign_unit: this.unitId,
                foreign_spot: this.eventId,
            })
            .then((res) => {
                this.items = res.data.status.map((res) => ({
                    value: res.bag_name === this.bagName ? "" : res.bag_name,
                    id: res.bag_name === this.bagName ? "" : res.id,
                    disable: res.bag_name === this.bagName ? true : false,
                }));
            });
    },
    methods: {
        logs(event) {
            axios
                .post("/add_logs", {
                    unit_id: this.unitId,
                    event_id: this.eventId,
                    ticket_id: this.globalTicketId,
                    descriptions: event,
                })
                .then((res) => {});
        },
        searchTicket(e) {
            this.bagList = e.target.value.split(",");
        },
        openModal() {
            this.id = this.editId[0];
            this.start = this.editId[1];
            this.start2 = this.editId[1];
            this.end = this.editId[2];
            this.end2 = this.editId[2];
            this.ticketName = this.editId[3];
            this.bags = this.editId[4];
            this.dialog = true;
        },
        async validate() {
            const name = localStorage.getItem("name");
            const insidePath = this.$route.path.split("/")[8];
            const { valid } = await this.$refs.form.validate();

            if (this.bagList === "") {
            } else if (valid) {
                this.loading = true;
                if (this.end - this.start + 1 === this.end2 - this.start2 + 1) {
                    const bagz = this.$route.query.event_id[3];
                    const transfer =
                        name +
                        " transfer the ticket from " +
                        bagz +
                        " to " +
                        Object.values(this.bagList)[0] +
                        " with the starting # of " +
                        this.start +
                        " to " +
                        this.end;
                    axios
                        .post("/update_bag_location", {
                            id: this.id,
                            start: this.start,
                            end: this.end,
                            bag: this.bagList[0],
                            bagId: this.bagList[1],
                            event: this.eventId,
                            unit: this.unitId,
                            ticketName: this.ticketName,
                            transfer: transfer,
                        })
                        .then((res) => {
                            this.logs(transfer);
                            this.$router.push({
                                path:
                                    "/administrator/dashboard/" +
                                    this.unitId +
                                    "/" +
                                    this.unitName.replace(/ /g, "_") +
                                    "/" +
                                    this.eventName.replace(/ /g, "_") +
                                    "/event_bags/inside_bag/loading",
                                query: {
                                    where: [
                                        this.eventId,
                                        String(this.bagId),
                                        this.sellerName,
                                        insidePath,
                                    ],
                                },
                            });
                        });
                }
                this.loading = false;
            }
        },
        reset() {
            this.dialog = false;

            this.$refs.form.reset();
        },
    },
};
</script>
