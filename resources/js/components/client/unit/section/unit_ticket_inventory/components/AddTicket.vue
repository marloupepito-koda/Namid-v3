<template>
    <div>
        <div>
            <v-card-text>
                <!-- <v-card-title>
                    <span class="text-h5">ADD UNIT TICKETS</span>
                </v-card-title> -->
                <v-form ref="form" v-model="valid" lazy-validation>
                    <div class="row">
                        <div class="col-md-4">Number of {{ bind }}</div>

                        <div class="col-md-8">{{ quantity }}</div>
                        <div class="col-md-4">Total Numbering</div>
                        <div class="col-md-8">
                            {{ end - start + 1 === 1 ? 0 : end - start + 1 }}
                        </div>
                        <p class="col-md-12 text-danger mt-2 mb-2 pt-5">
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

                    <div class="col-md-6 offset-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <v-btn color="error" @click="reset">
                                    CANCEL
                                </v-btn>
                            </div>
                            <div class="col-md-6">
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
                </v-form>
            </v-card-text>
        </div>
    </div>
</template>

<script>
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
                                        "/administrator/dashboard/" +
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
            this.modal = false;
            this.errorNotify = "";
        },
    },
};
</script>
