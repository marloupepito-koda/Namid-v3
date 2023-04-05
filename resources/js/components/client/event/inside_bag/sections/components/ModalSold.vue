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
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.date = moment().format("MM-DD-YYYY");
    },
    methods: {
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
            };
            if (valid) {
                if (localStorage.getItem("internet") === "offline") {
                    const form =
                        JSON.parse(
                            localStorage.getItem(
                                "create_ticket_sold" +
                                    this.unitId +
                                    this.eventId
                            )
                        ) === null
                            ? []
                            : JSON.parse(
                                  localStorage.getItem(
                                      "create_ticket_sold" +
                                          this.unitId +
                                          this.eventId
                                  )
                              );

                    form.push(data);
                    localStorage.setItem(
                        "create_ticket_sold" + this.unitId + this.eventId,
                        JSON.stringify(form)
                    );

                    this.$swal({
                        icon: "success",
                        title: "Ticket Added Sold",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                } else {
                    axios.post("/create_ticket_sold", data).then((res) => {
                        this.dialog = false;
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
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
