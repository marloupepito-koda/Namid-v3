<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="blue"
            @click="openModal(editId)"
        >
            <v-icon size="large" color="blue">
                mdi-clipboard-edit-outline
            </v-icon>
        </v-btn>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">Edit Ticket Price</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-12">
                                ATP:
                                <b>{{ editId.price / editId.count }}</b>
                            </div>
                            <div class="col-md-12">
                                Total Ticket: <b>{{ editId.const }}</b>
                            </div>
                            <div class="col-md-12 mb-2">
                                Ticket Counting:
                                <b>{{ editId.end - editId.start + 1 }}</b>
                            </div>
                            <!-- <div class="col-md-12 mb-5">
                                <div
                                    class="col-md-12 text-danger"
                                    v-if="
                                        editId.end - editId.start + 1 !==
                                        parseInt(editId.const)
                                    "
                                >
                                    The total ticket and ticket counting must be
                                    identical.
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="editId.ticket_type"
                                    :rules="ticketNameRules"
                                    label="Ticket Name"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="editId.price"
                                    :rules="ticketPriceRules"
                                    label="Ticket Price"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="editId.start"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    disabled
                                    v-model="editId.end"
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
                                :loading="loading"
                                @click="validate"
                            >
                                <!-- :disabled="
                                    parseInt(editId.end - editId.start + 1) ===
                                    parseInt(editId.const)
                                        ? false
                                        : true
                                " -->
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
    props: ["editId"],
    components: { Datepicker },
    data: () => ({
        loading: false,
        unitId: "",
        eventId: "",
        dialog: false,
        valid: true,
        ticketPriceRules: [(v) => !!v || "Ticket Price Name is required"],
        start: "",
        startRules: [(v) => !!v || "Starting # is required"],
        end: "",
        endRules: [(v) => !!v || "Ending # is required"],
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id;
    },
    methods: {
        openModal() {
            this.dialog = true;
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                axios
                    .put("/edit_ticket_inventory", {
                        id: this.editId.id,
                        start: this.editId.start,
                        end: this.editId.end,
                        price: this.editId.price,
                        count: this.editId.count,
                    })
                    .then((res) => {
                        this.loading = false;
                        this.dialog = false;
                        this.$swal({
                            icon: "success",
                            title: "Success!",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        this.tickets = res.data.status;
                    });
            }
        },
        reset() {
            // this.$refs.form.reset();
            this.dialog = false;
            this.dateRules = null;
            this.error = null;
        },
    },
};
</script>
