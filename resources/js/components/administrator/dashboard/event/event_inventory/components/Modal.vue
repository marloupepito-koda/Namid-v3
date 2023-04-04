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
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="ticket_name"
                                    :rules="ticket_nameRules"
                                    label="Ticket Type"
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
                                <v-checkbox
                                    style="height: 30px !important"
                                    v-model="numbered"
                                    label="Numbered"
                                ></v-checkbox>
                                <v-checkbox
                                    style="height: 30px !important"
                                    v-model="iatp"
                                    label="Include Average Ticket Price"
                                ></v-checkbox>
                                <v-checkbox
                                    style="height: 30px !important"
                                    v-model="tt"
                                    label="Transfer Unused Tickets to Next Day"
                                ></v-checkbox>
                                <v-checkbox
                                    style="height: 30px !important"
                                    v-model="pop"
                                    label="P.O.P"
                                ></v-checkbox>
                            </div>
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
                                            parseInt(end) > parseInt(end2) ||
                                            parseInt(start) < parseInt(start2)
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
import moment from "moment";
export default {
    props: ["editId"],
    created() {},
    mounted() {
        this.mount();
    },
    data: () => ({
        numbered: true,
        iatp: true,
        tt: true,
        pop: false,
        loading: false,
        items: [],
        dialog: false,
        valid: true,
        ticket_name: "",
        ticket_nameRules: [(v) => !!v || "Ticket Name is required"],
        price: "",
        priceRules: [(v) => !!v || "Price is required"],
        count: "",
        countRules: [(v) => !!v || "Count is required"],
        start: "",
        start2: "",
        startRules: [(v) => !!v || "Starting # is required"],
        end: "",
        end2: "",
        endRules: [(v) => !!v || "Ending # is required"],
        unitId: "",
        eventId: "",
        ticketId: "",
    }),

    methods: {
        searchTicket(e) {
            this.loading = true;
            axios
                .get(
                    "/api/get_each_ticket_in_unit/" +
                        e.target.value.split(",")[1]
                )
                .then((res) => {
                    this.ticketId = res.data.status.id;
                    this.ticket_name = res.data.status.ticket_name;
                    this.count = res.data.status.count;
                    this.start = res.data.status.start;
                    this.start2 = res.data.status.start;
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
            this.eventId = this.$route.path.split("/")[4];

            axios
                .get(
                    "/api/get_unit_inventory/" +
                        this.unitId +
                        "/" +
                        this.eventId
                )
                .then((res) => {
                    this.items = res.data.status.map((res) => ({
                        value: res.ticket_type,
                        id: res.id,
                    }));
                });
        },

        async validate() {
            const { valid } = await this.$refs.form.validate();
            const data = {
                id: this.ticketId,
                eventid: this.eventId,
                start: this.start,
                end: this.end,
                price: this.price,
                date: moment().format("LLL"),
                numbered: this.numbered,
                iatp: this.iatp,
                tt: this.tt,
                pop: this.pop,
            };

            if (valid) {
                this.loading = true;
                axios
                    .post("/add_ticket_in_event", data)
                    .then((res) => {
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.mount();
                        this.reset();
                        this.dialog = false;
                        this.loading = false;
                        this.$swal({
                            icon: "success",
                            title: "Ticket Added",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    })
                    .catch((err) => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        reset() {
            this.dialog = false;
            this.loading = false;
            this.$refs.form.reset();
        },
    },
};
</script>
