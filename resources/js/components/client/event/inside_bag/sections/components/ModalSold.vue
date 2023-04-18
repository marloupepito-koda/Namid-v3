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
                datas.status === 'Sold' || datas.seller === null ? true : false
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
                    <div v-if="internet === 'offline'" class="text-danger">
                        The connection are offline. <br />If you mark sold the
                        tickets, <br />You won't be able to revert this!
                    </div>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                {{ datas.ticket_type }}
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
                                    parseInt(start) > parseInt(end) ||
                                    parseInt(end) > parseInt(end2) ||
                                    parseInt(start) < parseInt(start2)
                                        ? true
                                        : false
                                "
                            >
                                SOLD
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
    props: ["datas"],
    components: { Datepicker },
    data: () => ({
        unitId: "",
        loading: false,
        eventId: "",
        date: moment().format("LLL"),
        start: "",
        end: "",
        start2: "",
        end2: "",
        dialog: false,
        id: "",
        quantity: "",
        bagId: "",
        internet: "",
    }),
    mounted() {
        this.mount();
    },
    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.mount();
            }
        );
    },
    methods: {
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];
            this.bagId = this.$route.path.split("/")[8];
            this.date = moment().format("MM-DD-YYYY");
            this.internet = localStorage.getItem("internet");
        },
        openModal() {
            this.dialog = true;
            this.start = this.datas.start;
            this.end = this.datas.end;
            this.start2 = this.datas.start;
            this.end2 = this.datas.end;
            this.id = this.datas.id;
            this.quantity = this.datas.quantity;
        },

        async validate() {
            const { valid } = await this.$refs.form.validate();
            const data = {
                id: this.id,
                start: this.start,
                end: this.end,
                date: this.date,
                quantity: this.quantity,
                data: this.datas,
            };
            if (valid) {
                if (this.internet === "offline") {
                    const ticketInbag = JSON.parse(
                        localStorage.getItem(
                            "get_tickets_in_bag" +
                                this.unitId +
                                this.eventId +
                                this.bagId
                        )
                    );
                    const sold = ticketInbag.find((t) => t.id === this.id);
                    if (
                        parseInt(data.start) - parseInt(sold.start) === 0 &&
                        parseInt(sold.end) - parseInt(data.end) !== 0
                    ) {
                        if (sold) {
                            // this is for remaining tickets
                            const remaining = {
                                id: Math.floor(Math.random() * 999999999999999),
                                unitid: this.unitId,
                                eventid: this.eventId,
                                ticket_type: sold.ticket_type,
                                bagid: this.bagId,
                                seller: sold.seller,
                                ticketid: sold.ticketid,
                                start: parseInt(data.end) + 1,
                                end: parseInt(sold.end),
                                price: sold.price,
                                bind: sold.bind,
                                count: sold.count,
                                date: sold.date,
                                quantity:
                                    parseInt(sold.end) - parseInt(data.end),
                                status: "Unsold",
                            };

                            if (remaining) {
                                //this is for sold ticket
                                sold.end = data.end;
                                (sold.status = "Sold"),
                                    (sold.quantity =
                                        parseInt(data.end) -
                                        parseInt(data.start) +
                                        1);
                                sold.date = data.date;
                                // end for sold ticket

                                // push method to insert the remaining tickets
                                // ticketInbag 'update the remaining tickets in a bag'
                                // remaining 'add tickets created'

                                //merge the updated ticket and remaining tickets
                                ticketInbag.push(remaining);

                                localStorage.setItem(
                                    "get_tickets_in_bag" +
                                        this.unitId +
                                        this.eventId +
                                        this.bagId,
                                    JSON.stringify(ticketInbag)
                                );

                                this.$router.push({
                                    path: this.$route.path,
                                    hash:
                                        "#" +
                                        Math.floor(Math.random() * 999999),
                                });
                                this.reset();
                            }
                        }
                    } else if (
                        parseInt(data.start) - parseInt(sold.start) !== 0 &&
                        parseInt(sold.end) - parseInt(data.end) === 0
                    ) {
                        // this is for remaining tickets
                        const remaining = {
                            id: Math.floor(Math.random() * 999999999999999),
                            unitid: this.unitId,
                            eventid: this.eventId,
                            ticket_type: sold.ticket_type,
                            bagid: this.bagId,
                            seller: sold.seller,
                            ticketid: sold.ticketid,
                            start: parseInt(sold.start),
                            end: parseInt(data.start) - 1,
                            price: sold.price,
                            bind: sold.bind,
                            count: sold.count,
                            date: sold.date,
                            quantity:
                                parseInt(data.start) - parseInt(sold.start),
                            status: "Unsold",
                        };

                        if (remaining) {
                            //this is for sold ticket
                            sold.start = data.start;
                            (sold.status = "Sold"),
                                (sold.quantity =
                                    parseInt(data.end) -
                                    parseInt(data.start) +
                                    1);
                            sold.date = data.date;
                            // end for sold ticket

                            // push method to insert the remaining tickets
                            // ticketInbag 'update the remaining tickets in a bag'
                            // remaining 'add tickets created'

                            //merge the updated ticket and remaining tickets
                            ticketInbag.push(remaining);

                            localStorage.setItem(
                                "get_tickets_in_bag" +
                                    this.unitId +
                                    this.eventId +
                                    this.bagId,
                                JSON.stringify(ticketInbag)
                            );

                            this.$router.push({
                                path: this.$route.path,
                                hash: "#" + Math.floor(Math.random() * 999999),
                            });
                            this.reset();
                        }
                    } else if (
                        parseInt(data.start) - parseInt(sold.start) !== 0 &&
                        parseInt(sold.end) - parseInt(data.end) !== 0
                    ) {
                        const firstQuantity =
                            parseInt(data.start) - parseInt(sold.start);
                        const firstStart = parseInt(sold.start);
                        const firstEnd = parseInt(data.start) - 1;

                        const remaining1 = {
                            id: Math.floor(Math.random() * 999999999999999),
                            unitid: this.unitId,
                            eventid: this.eventId,
                            ticket_type: sold.ticket_type,
                            bagid: this.bagId,
                            seller: sold.seller,
                            ticketid: sold.ticketid,
                            start: firstStart,
                            end: firstEnd,
                            price: sold.price,
                            bind: sold.bind,
                            count: sold.count,
                            date: sold.date,
                            quantity: firstQuantity,
                            status: "Unsold",
                        };
                        const endQuantity =
                            parseInt(sold.end) - parseInt(data.end);
                        const endStart = parseInt(data.end) + 1;
                        const endEnd = parseInt(sold.end);

                        const remaining2 = {
                            id: Math.floor(Math.random() * 999999999999999),
                            unitid: this.unitId,
                            eventid: this.eventId,
                            ticket_type: sold.ticket_type,
                            bagid: this.bagId,
                            seller: sold.seller,
                            ticketid: sold.ticketid,
                            start: endStart,
                            end: endEnd,
                            price: sold.price,
                            bind: sold.bind,
                            count: sold.count,
                            date: sold.date,
                            quantity: endQuantity,
                            status: "Unsold",
                        };

                        //this is for sold ticket
                        sold.start = data.start;
                        sold.end = data.end;
                        (sold.status = "Sold"),
                            (sold.quantity =
                                parseInt(data.end) - parseInt(data.start) + 1);
                        sold.date = data.date;
                        // end for sold ticket

                        ticketInbag.push(remaining1, remaining2);

                        localStorage.setItem(
                            "get_tickets_in_bag" +
                                this.unitId +
                                this.eventId +
                                this.bagId,
                            JSON.stringify(ticketInbag)
                        );

                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.reset();
                    } else {
                        sold.status = "Sold";
                        sold.date = data.date;
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.reset();
                    }
                } else {
                    axios.post("/create_ticket_sold", data).then((res) => {
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.reset();
                        this.$swal({
                            icon: "success",
                            title: "Added Ticket Sold",
                            showConfirmButton: false,
                            timer: 1000,
                        });
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
