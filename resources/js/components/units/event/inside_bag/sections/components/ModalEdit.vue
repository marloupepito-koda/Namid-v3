<template>
    <div class="text-center">
        <v-btn
            size="small"
            variant="outlined"
            large
            block
            color="blue"
            @click="openModal(editId)"
            :disabled="soldDate"
        >
            <v-icon large color="blue"> mdi-briefcase-edit-outline</v-icon>
        </v-btn>
        <v-dialog persistent v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">EDIT PRICING</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="price"
                            :rules="priceRules"
                            label="Price Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <div class="col-md-6 mt-5 offset-md-6 mb-3">
                            <v-btn color="error" class="mr-4" @click="reset">
                                CANCEL
                            </v-btn>

                            <v-btn
                                color="black"
                                :loading="loading"
                                class="mr-4"
                                @click="validate"
                            >
                                SAVE
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
        loading: false,
        unitId: "",
        eventId: "",
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        price: "",
        price2: "",
        priceRules: [(v) => !!v || "Price is required"],
        bagDestination: "",
        editNumberOfTicket: "",
        bagId: "",
        sellerName: "",
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id[0];
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.eventName = this.$route.path.split("/")[5];
        this.bagDestination = this.$route.path.split("/")[7];
        this.sellerName = this.$route.query.event_id[2];
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
        openModal() {
            this.editNumberOfTicket =
                parseInt(this.editId[3]) - parseInt(this.editId[2]) + 1;
            this.dialog = true;
            this.price = this.editId[4];
            this.price2 = this.editId[4];
            this.bagId = this.$route.query.event_id[1];
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            const date = moment(this.date).format("LLL");
            const datesss =
                new Date().getMonth() +
                1 +
                "-" +
                new Date().getDate() +
                "-" +
                new Date().getFullYear();
            const insidePath = this.$route.path.split("/")[8];
            if (valid) {
                this.loading = true;
                axios
                    .post("/edit_ticket_price", {
                        id: this.editId[0],
                        price: this.price,
                        atp: this.price / this.editId[5],
                        totalamount: this.editNumberOfTicket * this.price,
                    })
                    .then((res) => {
                        this.loading = false;
                        this.dialog = false;
                        const name = localStorage.getItem("name");
                        this.logs(
                            name +
                                " changed the price of " +
                                this.editId[1] +
                                " from " +
                                this.price2 +
                                " to " +
                                this.price
                        );
                        this.$swal({
                            icon: "success",
                            title: "saved",
                            showConfirmButton: false,
                            timer: 1500,
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
                                    insidePath,
                                    this.editId[1],
                                ],
                            },
                        });
                    });
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
    },
};
</script>
