<template>
    <div class="text-center">
        <v-btn
            size="small"
            block
            variant="outlined"
            large
            color="black"
            @click="openModal"
            :disabled="
                sellerName === null || sellerName === undefined
                    ? true
                    : soldDate
            "
        >
            <v-icon large color="black"> mdi-account-cash-outline</v-icon>
        </v-btn>
        <v-dialog persistent v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">SOLD TICKETS</span><br />
                    </v-card-title>
                    <span>SOLD: {{ parseInt(e) - parseInt(d) + 1 }}</span
                    ><br />
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <Datepicker
                                    v-model="date2"
                                    model-type="MM-dd-yyyy"
                                    placeholder="Select Date"
                                    input-class-name="dp-custom-input"
                                />
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="d"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="e"
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
                                    parseInt(limit) <
                                        parseInt(e) - parseInt(d) + 1 ||
                                    parseInt(e) < parseInt(d)
                                        ? true
                                        : parseInt(limit) <
                                              parseInt(e) - parseInt(d) + 1 ||
                                          parseInt(e) < parseInt(d)
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
    props: ["editId", "soldDate"],
    components: { Datepicker },
    data: () => ({
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
        end: "",
        endRules: [(v) => !!v || "Ending # is required"],
        bagDestination: "",
        bagId: "",
        a: "",
        b: "",
        c: "",
        d: "",
        e: "",
        f: "",
        g: "",
        global_ticket_id: "",
        ticketId: "",
        ticketPricez: "",
        ticketends: "",
        soldDisable: "",
        date2: moment().format("LLL"),
        sellerName: "",
        limit: "",
        atp: "",
        random: "",
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id[0];
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[7];
        this.bagId = this.$route.query.event_id[1];
        this.sellerName = this.$route.query.event_id[2];
    },
    methods: {
        openModal() {
            this.random = this.editId[14];
            this.dialog = true;
            this.start = this.bag;
            this.bagids = this.editId[0];
            this.global_ticket_id = this.editId[10];
            this.b = this.editId[1];
            this.bagnames = this.editId[2];
            this.d = this.editId[9];
            this.e = this.editId[4];
            this.ticketId = this.editId[6];
            this.ticketPricez = this.editId[5];
            this.ticketends = this.editId[4];
            this.atp = this.editId[12];
            this.soldDisable = this.editId[7] === "Sold Out" ? true : false;
            const d = new Date(this.date);
            this.g = moment(d).format("LLL");
            this.limit = this.editId[4] - this.editId[9] + 1;
        },
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
        async validate() {
            const name = localStorage.getItem("name");
            const { valid } = await this.$refs.form.validate();
            const d = new Date(this.date2);
            const date = moment(d).format("LLL");
            const datesss =
                new Date().getMonth() +
                1 +
                "-" +
                new Date().getDate() +
                "-" +
                new Date().getFullYear();
            const global_ticket_id = this.global_ticket_id;
            const insidePath = this.$route.path.split("/")[8];

            const data = {
                bag_id: this.bagids,
                unit_id: this.unitId,
                spot_id: this.eventId,
                global_ticket_id: global_ticket_id,
                inventory_bags_id: this.bagids,
                ticket_name: this.b,
                bagname: this.bagnames,
                ticket_price: this.ticketPricez,
                ticket_start: this.d,
                atp: this.atp,
                ticket_end: parseInt(this.e),
                ticket_date_sold: this.date2,
                remaining: this.ticketends - this.d + 1 - (this.e - this.d + 1),
                amount: this.ticketPricez * (this.e - this.d + 1),
                status:
                    this.ticketends - this.d + 1 - (this.e - this.d + 1) === 0
                        ? "soldout"
                        : "keepit",
                sellerName: this.sellerName,
                random: this.random,
            };
            if (valid) {
                this.loading = true;

                const form =
                    JSON.parse(
                        localStorage.getItem(
                            "add_ticket_sold" +
                                this.unitId +
                                this.eventId +
                                this.bagId
                        )
                    ) === null
                        ? []
                        : JSON.parse(
                              localStorage.getItem(
                                  "add_ticket_sold" +
                                      this.unitId +
                                      this.eventId +
                                      this.bagId
                              )
                          );

                if (localStorage.getItem("internet") !== "online") {
                    const ticketList = JSON.parse(
                        localStorage.getItem(
                            "add_ticket_bag" +
                                this.unitId +
                                this.eventId +
                                this.bagId
                        )
                    );
                    const update = ticketList.find(
                        (t) => t.random === data.random
                    );

                    update.ticket_start = data.ticket_end + 1;
                    update.start = data.ticket_start;
                    update.end = data.ticket_end;
                    localStorage.setItem(
                        "add_ticket_bag" +
                            this.unitId +
                            this.eventId +
                            this.bagId,
                        JSON.stringify(ticketList)
                    );
                    form.push(update);
                    localStorage.setItem(
                        "add_ticket_sold" +
                            this.unitId +
                            this.eventId +
                            this.bagId,
                        JSON.stringify(form)
                    );

                    this.dialog = false;
                    this.loading = false;
                    this.$swal({
                        icon: "success",
                        title: "Added Ticket Sold",
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
                            "/event_bags/inside_bag/loading",
                        query: {
                            where: [
                                this.eventId,
                                String(this.bagId),
                                this.sellerName,
                                insidePath + "/offline",
                            ],
                        },
                    });
                } else {
                    axios.post("/add_ticket_sold", data).then((res) => {
                        this.loading = false;
                        this.dialog = false;
                        if (res.data.status === "error") {
                            this.$swal({
                                icon: "error",
                                title: "The ending of the ticket is exceed!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        } else {
                            this.$swal({
                                icon: "success",
                                title: "Tickets Sold!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.logs(
                                name +
                                    " marked as sold " +
                                    this.b +
                                    " in " +
                                    this.c +
                                    " with starting # of " +
                                    this.d +
                                    " to " +
                                    this.e +
                                    " the amount of" +
                                    this.ticketPricez * (this.e - this.d + 1)
                            );
                            this.$router.push({
                                path:
                                    "/unit/branch/" +
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
                        }
                    });
                }
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
    },
};
</script>
