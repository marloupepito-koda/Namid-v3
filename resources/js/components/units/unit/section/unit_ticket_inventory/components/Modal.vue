<template>
    <div>
        <v-btn color="black" @click="dialog = true"> ADD UNIT TICKETS </v-btn>
        <v-dialog
            v-model="dialog"
            width="500"
            class="modalMargin offset-md-8"
            style="margin-right: 0px !important; padding: 0px"
        >
            <template v-slot:activator="{ props }">
                <!-- <v-btn color="black" block v-bind="props">
                    ADD UNIT TICKETS
                </v-btn> -->
            </template>

            <v-card style="height: 100vh">
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">ADD UNIT TICKETS</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-5">Number of {{ bind }}</div>

                            <div class="col-md-7">{{ quantity }}</div>
                            <div class="col-md-5">Total Numbering</div>
                            <div class="col-md-7">
                                {{
                                    end - start + 1 === 1 ? 0 : end - start + 1
                                }}
                            </div>
                            <p class="col-md-12 text-danger mt-2 mb-5">
                                {{ errorNotify }}
                            </p>
                            <div class="col-md-4 col-6">
                                <v-text-field
                                    v-model="ticketType"
                                    :rules="ticketTypeRules"
                                    label="Ticket Type"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-4 col-6">
                                <v-text-field
                                    v-model="ticketName"
                                    :rules="ticketNameRules"
                                    label="Ticket Name"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-4 col-6">
                                <v-text-field
                                    v-model="count"
                                    :rules="countRules"
                                    label="Count"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-6">
                                <v-select
                                    v-model="bind"
                                    :items="items"
                                    item-title="state"
                                    label="Bind Name"
                                    variant="outlined"
                                    density="compact"
                                    :rules="bindRules"
                                    required
                                ></v-select>
                            </div>
                            <div class="col-md-6 col-6">
                                <v-text-field
                                    type="number"
                                    v-model="quantity"
                                    :rules="quantityRules"
                                    label="Quantity"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-6">
                                <v-text-field
                                    type="number"
                                    v-model="start"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-6">
                                <v-text-field
                                    type="number"
                                    v-model="end"
                                    :rules="endRules"
                                    label="Ending #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-12 col-12">
                                <v-checkbox
                                    label="Numbered"
                                    v-model="numbered"
                                ></v-checkbox>
                            </div>
                        </div>
                    </v-form>
                </v-card-text>
                <div style="position: absolute; bottom: 0; right: 0">
                    <div class="row p-3">
                        <div class="col-md-6 col-6">
                            <v-btn color="error" @click="reset"> CANCEL </v-btn>
                        </div>
                        <div class="col-md-6 col-6">
                            <v-btn
                                color="black"
                                :loading="loading"
                                :disabled="
                                    parseInt(start) > parseInt(end)
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
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
export default {
    data: () => ({
        loading: false,
        items: [
            { state: "Roll" },
            { state: "Sheet" },
            { state: "Book" },
            { state: "Wristband" },
        ],
        dialog: false,
        valid: true,
        ticketType: "",
        ticketTypeRules: [(v) => !!v || "required"],
        ticketName: "",
        ticketNameRules: [(v) => !!v || "required"],
        count: "",
        countRules: [(v) => !!v || "required"],
        bind: "",
        bindRules: [(v) => !!v || "required"],
        quantity: "",
        quantityRules: [(v) => !!v || "required"],
        start: "",
        startRules: [(v) => !!v || "required"],
        end: "",
        endRules: [(v) => !!v || "required"],
        numbered: "",
        unitId: "",
        eventId: "",
        unitName: "",
        eventName: "",
        errorNotify: "",
    }),

    mounted() {
        this.mount();
    },

    methods: {
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.query.event_id;
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");
        },
        logs(event) {
            axios
                .post("/add_logs", {
                    unit_id: this.unitId,
                    event_id: "",
                    ticket_id: "",
                    descriptions: event,
                })
                .then((res) => {});
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                const validValue = this.quantity;
                // const state =
                //     this.bind === "Roll"
                //         ? (this.end - this.start + 1) / this.quantity ===
                //               1000 ||
                //           (this.end - this.start + 1) / this.quantity === 4000
                //         : validValue === this.end - this.start + 1;
                if (parseInt(validValue) === this.end - this.start + 1) {
                    axios
                        .post("/ee_add_global_ticket", {
                            numbered: this.numbered,
                            ticket_foreign_unit: this.unitId,
                            tickettypenumber: this.ticketType,
                            ticketname: this.ticketName,
                            bind_name: this.bind,
                            ticketcount: this.count,
                            ticketntrsb: this.end - this.start + 1,
                            ticketstart: this.start,
                            ticketend: this.end,
                            ticketdate: moment().format("LLL"),
                        })
                        .then((res) => {
                            this.loading = false;
                            if (res.data.status === "exist") {
                                this.errorNotify =
                                    "Ticket Type is already exist!";
                            } else {
                                const name = localStorage.getItem("name");
                                this.logs(
                                    name +
                                        " added " +
                                        this.ticketName +
                                        " with the starting # of " +
                                        this.start +
                                        " the ending of " +
                                        this.end +
                                        " and the total of " +
                                        (this.end - this.start + 1) +
                                        " Tickets"
                                );
                                this.rows2 = res.data.status;
                                this.$swal({
                                    icon: "success",
                                    title: "Ticket Added!",
                                    showConfirmButton: false,
                                    timer: 1000,
                                });
                                this.errorNotify = "";
                                this.$router.push({
                                    path:
                                        "/unit/branch/" +
                                        this.unitId +
                                        "/" +
                                        this.unitName.replace(/ /g, "_") +
                                        "/loading",
                                    query: { where: "unit_ticket_inventory" },
                                });
                            }
                        })
                        .catch((err) => {
                            this.loading = false;
                        });
                } else {
                    // if (this.bind === "Roll") {
                    //     this.errorNotify =
                    //         "Number of " +
                    //         this.bind +
                    //         " must be either 1000 or 4000";
                    // } else {

                    // }
                    this.errorNotify =
                        "Number of " +
                        this.bind +
                        " must be equal in Total Numbering!";
                    this.loading = false;
                }
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
            this.errorNotify = "";
        },
    },
};
</script>
