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
                            >ADD TICKET IN A {{ bagsss }}</span
                        >
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- <v-select
                                    :items="itemsList"
                                    v-model="ticketName"
                                    item-title="value"
                                    label="Ticket Name"
                                    variant="outlined"
                                    density="compact"
                                    :rules="ticketNameRules"
                                    required
                                   
                                ></v-select> -->
                                <select
                                    @change="searchTicket"
                                    class="form-select"
                                >
                                    <option selected disabled>
                                        Select Tickets
                                    </option>
                                    <option
                                        v-for="i in itemsList"
                                        :value="[
                                            i.ee_ticket_id,
                                            i.ticket_name,
                                            i.ticket_number,
                                            i.random,
                                        ]"
                                        :class="
                                            i.status === '0' ? 'd-none' : ''
                                        "
                                    >
                                        {{ i.ticket_number }}
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
                                color="black"
                                :loading="loading"
                                :disabled="
                                    start > end || end > end2 ? true : false
                                "
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
        ticketName: "",
        ticketNameRules: [(v) => !!v || "Ticket Name is required"],
        price: "",
        priceRules: [(v) => !!v || "Price # is required"],
        start: "",
        startRules: [(v) => !!v || "Starting #  is required"],
        end: "",
        endRules: [(v) => !!v || "Ending # is required"],
        end2: "",
        bagDestination: "",
        itemsList: [],
        seller: [],
        ticketId: "",
        bagId: "",
        bindName: "",
        ticketCount: "",
        from: "",
        to: "",
        subid: "",
        bagsss: "",
        pendings: [],
        selected: [],
        ticketNumbers: "",
        random: "",
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId =
            Array.isArray(this.$route.query.event_id) === true
                ? this.$route.query.event_id[0]
                : this.$route.query.event_id;
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[8];
        this.bagId = this.$route.query.event_id[1];
        this.sellerName = this.$route.query.event_id[2];
        this.bagsss = this.$route.query.event_id[3];
        const date =
            new Date().getMonth() +
            1 +
            "-" +
            new Date().getDate() +
            "-" +
            new Date().getFullYear();
        axios
            .get("/api/get_all_ticket3/" + [this.unitId, this.eventId])
            .then((res) => {
                this.pendings = JSON.parse(
                    localStorage.getItem(
                        "ee_add_ticket" + this.unitId + this.eventId
                    )
                );

                this.itemsList = res.data.status;
                let urls = [
                    "/api/get_all_ticket3/" + [this.unitId, this.eventId],
                ];

                const max = Math.max.apply(
                    Math,
                    res.data.status.map((reszzz) =>
                        parseInt(reszzz.ee_ticket_id)
                    )
                );

                if (
                    localStorage.getItem("internet") !== "online" &&
                    this.pendings !== null
                ) {
                    for (let ip = 0; ip < this.pendings.length; ip++) {
                        let mapPendings = [
                            "/api/get_all_ticket2/" +
                                [
                                    this.unitId,
                                    this.eventId,
                                    parseInt(max) + ip + 1,
                                ],
                        ];
                        caches.open("static_cache").then((cache) => {
                            cache.addAll(mapPendings).then(() => {});
                        });
                    }

                    this.itemsList.push(
                        ...this.pendings.map((res, index) => ({
                            ticket_number: res.tickettypenumber,
                            ee_ticket_id: max + index + 1,
                            start: res.start,
                            end: res.end,
                            price: res.ticketprice,
                            globalTicketId: res.globalTicketId,
                            foreign_unit: this.unitId,
                            foreign_spot: this.eventId,
                            inventory_bags_id: this.bagId,
                            bag_id: this.bagId,
                            ticket_price: res.ticketprice,
                            ticket_name: res.ticketname,
                            seller_name: this.sellerName,
                            ticket_start: res.start,
                            ticket_end: res.end,
                            bagName: this.bagId,
                            date: date,
                            bind_name: res.bind_name,
                            ticket_count: res.ticketcount,
                            atp: res.ticketprice / res.ticketcount,
                            from: res.ticketstart,
                            to: res.ticketend,
                            subid: res.globalTicketId,
                            random: res.random,
                        }))
                    );
                }
                caches.open("static_cache").then((cache) => {
                    cache.addAll(urls).then(() => {
                        console.log("Data cached ");
                    });
                });

                //  const children = this.pendings.concat(this.itemsList);
                // this.itemsList = res.data.status.map(
                //     (zzz) => zzz.ticket_name + "-" + zzz.ee_ticket_id
                // );
                //  console.log(Object.values(children));
            });
    },
    methods: {
        searchTicket(e) {
            const ticketid = e.target.value.split(",")[0];
            this.ticketName = e.target.value.split(",")[1];
            this.ticketNumbers = e.target.value.split(",")[2];
            const randoms = e.target.value.split(",")[3];

            const selected = this.itemsList.find(
                (item) => item.random === parseInt(randoms)
            );
            if (selected === undefined) {
                axios
                    .get(
                        "/api/get_all_ticket2/" +
                            [this.unitId, this.eventId, parseInt(ticketid)]
                    )
                    .then((res) => {
                        const selected = res.data.status;
                        this.selected = selected;
                        console.log("selected2", selected);
                        let urls = [
                            "/api/get_all_ticket2/" +
                                [this.unitId, this.eventId, parseInt(ticketid)],
                        ];

                        caches.open("static_cache").then((cache) => {
                            cache.addAll(urls).then(() => {});
                        });
                        this.globalTicketId = parseInt(
                            res.data.status.global_ticket_id
                        );
                        this.start =
                            parseInt(res.data.status.end) -
                            parseInt(res.data.status.status) +
                            1;
                        this.end = parseInt(res.data.status.end);
                        this.end2 = parseInt(res.data.status.end);
                        this.ticketId = res.data.status.ee_ticket_id;
                        this.price = res.data.status.ticket_price;
                        this.bindName = res.data.status.bind_name;
                        this.ticketCount = parseInt(
                            res.data.status.ticket_count
                        );
                        this.from = res.data.status.ticket_start;
                        this.to = res.data.status.ticket_end;
                        this.subid = res.data.status.global_ticket_id;
                    });
                // .catch((err) => {
                //     this.price = selected.price;
                //     this.start = selected.start;
                //     this.end = selected.end;
                //     this.end2 = selected.end2;
                //     this.ticketCount = selected.ticket_count;
                //     this.selected = selected;
                // });
            } else {
                this.price = selected.price;
                this.start = selected.start;
                this.end = selected.end;
                this.end2 = selected.end2;
                this.ticketCount = selected.ticket_count;
                this.selected = selected;
                this.random = selected.random;
            }
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
            const { valid } = await this.$refs.form.validate();
            const date =
                new Date().getMonth() +
                1 +
                "-" +
                new Date().getDate() +
                "-" +
                new Date().getFullYear();
            const data = {
                ticket_number: this.ticketNumbers,
                globalTicketId: this.globalTicketId,
                foreign_unit: this.unitId,
                foreign_spot: this.eventId,
                ee_ticket_id: this.ticketId,
                inventory_bags_id: this.bagId,
                bag_id: this.bagId,
                ticket_price: this.price,
                ticket_name: this.ticketName,
                seller_name: this.sellerName,
                ticket_start: parseInt(this.start),
                ticket_end: parseInt(this.end),
                bagName: this.bagId,
                date: date,
                bind_name: this.bindName,
                ticket_count: this.ticketCount,
                atp: this.price / this.ticketCount,
                from: this.from,
                to: this.to,
                subid: this.subid,
                random: this.random,
            };
            if (valid) {
                this.loading = true;
                if (localStorage.getItem("internet") !== "online") {
                    const form =
                        JSON.parse(
                            localStorage.getItem(
                                "add_ticket_bag" +
                                    this.unitId +
                                    this.eventId +
                                    this.bagId
                            )
                        ) === null
                            ? []
                            : JSON.parse(
                                  localStorage.getItem(
                                      "add_ticket_bag" +
                                          this.unitId +
                                          this.eventId +
                                          this.bagId
                                  )
                              );

                    form.push(data);
                    localStorage.setItem(
                        "add_ticket_bag" +
                            this.unitId +
                            this.eventId +
                            this.bagId,
                        JSON.stringify(form)
                    );

                    console.log(data);

                    this.dialog = false;
                    this.loading = false;
                    this.$swal({
                        icon: "success",
                        title: "Ticket Added",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                } else {
                    axios
                        .post("/add_ticket_bag", data)
                        .then((res) => {
                            const name = localStorage.getItem("name");
                            this.logs(
                                name +
                                    " added ticket " +
                                    this.ticketName +
                                    " in " +
                                    this.bagsss +
                                    " with starting # of " +
                                    this.start +
                                    " and ending of " +
                                    this.end
                            );
                            this.end = "";
                            this.ticketName = "";
                            this.loading = false;
                            this.dialog = false;
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
                                        this.bagDestination,
                                    ],
                                },
                            });

                            this.$swal({
                                icon: "success",
                                title: "Tickets added in a bag.",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        })
                        .catch((err) => {
                            this.loading = false;
                        });
                }
            }
        },
        reset() {
            this.ticketName = "";
            this.start = "";
            this.end = "";
            this.price = "";
            this.dialog = false;
        },
    },
};
</script>
