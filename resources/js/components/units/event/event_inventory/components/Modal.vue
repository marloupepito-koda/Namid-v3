<template>
    <div class="text-center">
        <v-btn color="black" block @click="dialog = true"> ADD TICKETS </v-btn>
        <v-dialog
            v-model="dialog"
            width="500"
            class="modalMargin offset-md-8"
            style="margin-right: 0px !important; padding: 0px"
        >
            <template v-slot:activator="{ props }">
                <!-- <v-btn color="black" block v-bind="props"> ADD TICKETS </v-btn> -->
            </template>
            <v-card style="height: 100vh">
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">ADD TICKETS</span>
                    </v-card-title>
                    Price of Ticket Roll/Sheet/Book: $ {{ price }} <br />
                    ATP: $ {{ price / count }} <br />
                    POP: $ {{ price }}
                    <!-- <div class="col-md-12">
                        <b class="text-danger">
                            {{ start > end ? "The Tickets" : "" }}
                        </b>
                    </div> -->
                    <hr />

                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-6">
                                <select
                                    class="form-select"
                                    @change="searchTicket"
                                >
                                    <option selected disabled></option>
                                    <option
                                        :value="[i.value, i.id]"
                                        v-for="i in items"
                                        :key="i.id"
                                    >
                                        {{ i.value }}
                                    </option>
                                </select>

                                <!-- <v-select
                                    :items="items"
                                    item-title="value"
                                    label="Ticket Type List"
                                    variant="outlined"
                                    density="compact"
                                    :rules="ticketTypeRules"
                                    required
                                    :loading="loading"
                                    @update:modelValue="searchTicket"
                                ></v-select> -->
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="ticketName"
                                    :rules="ticketNameRules"
                                    label="Ticket Name"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="price"
                                    :rules="priceRules"
                                    label="Price"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>

                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="count"
                                    :rules="countRules"
                                    label="Count"
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
                            <div class="col-md-12">
                                <!-- <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                Unit Inventory Total
                                            </th>
                                            <th scope="col">
                                                {{ ticket.bind_name }}
                                            </th>
                                            <th scope="col">{{ status }}</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                Event Ticket Entry
                                            </th>
                                            <th scope="col">
                                                {{ ticket.bind_name }}
                                            </th>
                                            <th scope="col">
                                                {{
                                                    ticket.end -
                                                    ticket.start +
                                                    1
                                                }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="col">
                                                Unit Ticket Remaining
                                            </th>
                                            <th scope="col">
                                                {{ ticket.bind_name }}
                                            </th>
                                            <th scope="col">
                                                {{
                                                    status -
                                                    (ticket.end -
                                                        ticket.start +
                                                        1)
                                                }}
                                            </th>
                                        </tr>
                                    </thead>
                                </table> -->
                            </div>
                            <!-- <div class="col-md-12 mt-5">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="check1"
                                        id="flexCheckDefault1"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="flexCheckDefault1"
                                    >
                                        Include in Average Ticket Price
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="check2"
                                        id="flexCheckDefault2"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="flexCheckDefault2"
                                    >
                                        Transfer Unused Tickets to Next Day
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        v-model="check3"
                                        id="flexCheckDefault3"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="flexCheckDefault3"
                                    >
                                        P.O.P.
                                    </label>
                                </div>
                            </div> -->
                        </div>

                        <div style="position: absolute; bottom: 0; right: 0">
                            <div class="row p-3">
                                <div class="col-md-6 col-6">
                                    <v-btn
                                        color="error"
                                        class="mr-4"
                                        @click="reset"
                                    >
                                        CANCEL
                                    </v-btn>
                                </div>
                                <div class="col-md-6 col-6">
                                    <v-btn
                                        color="black"
                                        class="mr-4"
                                        :loading="loading"
                                        :disabled="
                                            parseInt(start) > parseInt(end) ||
                                            parseInt(end) > parseInt(end2)
                                                ? true
                                                : false
                                        "
                                        @click="validate"
                                    >
                                        SUBMIT
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props: ["editId"],
    created() {},
    mounted() {
        this.mount();
    },
    data: () => ({
        loading: false,
        items: [],
        dialog: false,
        valid: true,
        ticketType: null,
        ticketTypeRules: [(v) => !!v || "Ticket Type is required"],
        ticketName: "",
        ticketNameRules: [(v) => !!v || "Ticket Name is required"],
        price: "",
        priceRules: [(v) => !!v || "Price is required"],
        count: "",
        countRules: [(v) => !!v || "Count is required"],
        start: "",
        startRules: [(v) => !!v || "Starting # is required"],
        end: "",
        end2: "",
        endRules: [(v) => !!v || "Ending # is required"],
        check1: false,
        check2: false,
        check3: false,
        unitId: "",
        eventId: "",
        unitName: "",
        eventName: "",
        globalTicketId: "",
        ticketntrsb: "",
        bind_name: "",
        status: "",
        warn: "",
    }),

    methods: {
        logs(event) {
            axios
                .post("/add_logs", {
                    unit_id: this.unitId,
                    event_id: this.eventId,
                    ticket_id: "",
                    descriptions: event,
                })
                .then((res) => {});
        },
        searchTicket(e) {
            this.loading = true;
            axios
                .get(
                    "/api/get_every_global_ticket/" +
                        [
                            e.target.value.split(",")[1],
                            this.unitId,
                            this.eventId,
                        ]
                )
                .then((res) => {
                    let urls = [
                        "/api/get_every_global_ticket/" +
                            [
                                e.target.value.split(",")[1],
                                this.unitId,
                                this.eventId,
                            ],
                    ];

                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });

                    this.globalTicketId = res.data.status.ee_ticket_id;
                    this.ticketntrsb = res.data.status.ticket_ntrsb;
                    this.bind_name = res.data.status.bind_name;
                    this.ticketType = e.target.value.split(",")[0];
                    this.ticketName = res.data.status.ticket_name;
                    this.status =
                        res.data.status.end - res.data.status.start + 1;
                    this.count = res.data.status.ticket_count;
                    this.start = res.data.status.start;
                    this.end = res.data.status.end;
                    this.end2 = res.data.status.end;
                    this.loading = false;
                })
                .catch((err) => {
                    this.loading = false;
                });
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");
            this.eventId =
                Array.isArray(this.$route.query.event_id) === true
                    ? this.$route.query.event_id[0]
                    : this.$route.query.event_id;
            axios
                .get(
                    "/api/ee_get_all_global_ticket/" +
                        this.unitId +
                        "," +
                        this.eventName.replace(/ /g, "_")
                )
                .then((res) => {
                    let urls = [
                        "/api/ee_get_all_global_ticket/" +
                            this.unitId +
                            "," +
                            this.eventName.replace(/ /g, "_"),
                    ];

                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });

                    this.items = res.data.status.map((res) => ({
                        value: res.ticket_number,
                        id: res.ee_ticket_id,
                    }));
                });
        },

        openModal() {
            axios
                .post("/get_edit_event", {
                    id: this.editId,
                })
                .then((res) => {
                    this.editData = res.data.status;
                    this.dialog = true;
                });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();

            const data = {
                globalTicketId: this.globalTicketId,
                ticket_foreign_unit: this.unitId,
                ticket_foreign_spot: this.eventId,
                tickettypenumber: this.ticketType,
                ticketname: this.ticketName,
                ticketprice: this.price,
                ticketcount: this.count,
                ticketntrsb: this.ticketntrsb,
                bind_name: this.bind_name,
                ticketstart: parseInt(this.start),
                ticketend: parseInt(this.end),
                start: parseInt(this.start),
                end: parseInt(this.end),
                status: this.status,
                check1: this.check1,
                check2: this.check2,
                check3: this.check3,
                eventName: this.eventName,
                random:
                    localStorage.getItem("internet") !== "online"
                        ? Math.floor(Math.random() * 100000000)
                        : "online",
            };

            if (localStorage.getItem("internet") !== "online") {
                const form =
                    JSON.parse(
                        localStorage.getItem(
                            "ee_add_ticket" + this.unitId + this.eventId
                        )
                    ) === null
                        ? []
                        : JSON.parse(
                              localStorage.getItem(
                                  "ee_add_ticket" + this.unitId + this.eventId
                              )
                          );

                form.push(data);
                localStorage.setItem(
                    "ee_add_ticket" + this.unitId + this.eventId,
                    JSON.stringify(form)
                );

                this.dialog = false;
                this.loading = false;
                this.$swal({
                    icon: "success",
                    title: "Ticket Added",
                    showConfirmButton: false,
                    timer: 1000,
                });
                // console.log(form);
                // localStorage.removeItem("ee_add_ticket");
            } else {
                if (valid) {
                    this.loading = true;
                    axios
                        .post("/ee_add_ticket", data)
                        .then((res) => {
                            this.mount();
                            const name = localStorage.getItem("name");
                            this.logs(
                                name +
                                    " added " +
                                    this.ticketName +
                                    " from starting # " +
                                    this.start +
                                    " to " +
                                    this.end +
                                    " and the total of " +
                                    (this.end - this.start + 1) +
                                    " Tickets " +
                                    "with the amount of " +
                                    this.price
                            );
                            this.dialog = false;
                            this.loading = false;
                            this.$swal({
                                icon: "success",
                                title: "Ticket Added",
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
                                        "event_inventory",
                                        [String(this.eventId)],
                                    ],
                                },
                            });
                        })
                        .catch((err) => {
                            this.loading = false;
                            console.log(err);
                        });
                }
            }
        },
        reset() {
            this.dialog = false;
            this.loading = false;
            //  this.$refs.form.reset();
        },
    },
};
</script>
