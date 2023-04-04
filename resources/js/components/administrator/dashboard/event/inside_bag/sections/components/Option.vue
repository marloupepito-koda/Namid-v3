<template>
    <div class="text-center">
        <v-btn
            size="small"
            block
            variant="outlined"
            color="black"
            :loading="loading"
            @click="openModal"
            :disabled="datas.status === 'Sold' ? true : false"
        >
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
                            <div class="col-md-12 mb-5">
                                <select
                                    class="form-select"
                                    @change="searchTicket"
                                >
                                    <option disabled selected>
                                        Select Bag
                                    </option>
                                    <option
                                        v-for="i in items"
                                        :value="[i.bag_name, i.id]"
                                        :key="i.id"
                                    >
                                        {{ i.bag_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <v-text-field
                                    v-model="ticket_type"
                                    :rules="ticket_typeRules"
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
                                    parseInt(endDefault) < parseInt(end)
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
    props: ["datas"],
    components: { Datepicker },
    data: () => ({
        dialog: false,
        ticket_type: "",
        ticket_typeRules: [(v) => !!v || "Ticket Name is required"],
        startRules: [(v) => !!v || "Starting # is required"],
        endRules: [(v) => !!v || "Ending # is required"],
        start: "",
        end: "",
        unitId: "",
        eventId: "",
        bagId: "",
        bagName: "",
        newBagId: "",
        ticketId: "",
        quantity: "",
        loading: false,
        endDefault: "",
        transfer: false,
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.bagId = this.$route.path.split("/")[8];
    },
    methods: {
        searchTicket(e) {
            this.bagName = e.target.value.split(",")[0];
            this.newBagId = e.target.value.split(",")[1];
            this.ticket_type = this.datas.ticket_type;
            this.start = this.datas.start;
            this.end = this.datas.end;
            this.endDefault = this.datas.end;
            this.ticketId = this.datas.id;
            this.quantity = this.datas.quantity;
        },
        openModal() {
            this.loading = true;
            axios
                .get(
                    "/api/get_event_bags/" +
                        this.unitId +
                        "/" +
                        this.eventId +
                        "/all"
                )
                .then((res) => {
                    this.false = true;
                    this.dialog = true;
                    this.loading = false;
                    const bagList = res.data.status;
                    const indexToRemove = bagList.findIndex(
                        (obj) => obj.id === parseInt(this.bagId)
                    );
                    bagList.splice(indexToRemove, 1);
                    this.items = bagList;
                });
        },
        async validate() {
            const name = localStorage.getItem("name");
            const { valid } = await this.$refs.form.validate();

            if (valid) {
                this.loading = false;
                axios
                    .put("/transfer_ticket_in_another_bag", {
                        bagid: this.newBagId,
                        ticketid: this.ticketId,
                        ticket_type: this.ticket_type,
                        start: this.start,
                        end: this.end,
                        quantity: parseInt(this.end) - parseInt(this.start) + 1,
                        transfer: this.transfer,
                        endDefault: this.endDefault,
                    })
                    .then((res) => {
                        this.reset();
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.$swal({
                            icon: "success",
                            title: "Transferred success!",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    });
            }
        },
        reset() {
            this.dialog = false;
            this.$refs.form.reset();
            this.bagName = "";
        },
    },
};
</script>
