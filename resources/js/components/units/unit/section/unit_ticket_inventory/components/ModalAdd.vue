<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="black"
            @click="openModal(ticketId)"
        >
            <v-icon size="large"> mdi-card-plus-outline </v-icon>
        </v-btn>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title class="mb-3">
                        <span class="text-h5">Designate ticket in event</span>
                    </v-card-title>

                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <v-select
                                    v-model="eventName"
                                    :items="items"
                                    :rules="eventNameRules"
                                    item-title="text"
                                    label="Event Name"
                                    variant="outlined"
                                    density="compact"
                                    required
                                    @update:modelValue="chooseEvent"
                                ></v-select>
                            </div>
                            <div class="col-md-6 col-6">
                                <v-text-field
                                    v-model="start"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    disabled
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6 col-6">
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
                                    parseInt(start) > parseInt(end) ||
                                    parseInt(end) > parseInt(end2)
                                        ? true
                                        : false
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
export default {
    props: ["ticketId"],
    created() {},
    mounted() {
        this.mount();
    },
    data: () => ({
        loading: false,
        items: [],
        unitId: "",
        addId: "",
        dialog: false,
        valid: true,
        unitName: "",
        eventName: "",
        eventNameRules: [(v) => !!v || "Event Name is required"],
        start: "",
        startRules: [(v) => !!v || "Starting # is required"],
        end: "",
        endRules: [(v) => !!v || "Ending # is required"],
        end2: "",
    }),

    methods: {
        chooseEvent(e) {
            axios
                .post("/get_every_global_ticket", {
                    unitId: this.unitId,
                    ticketid: this.ticketId,
                })
                .then((res) => {
                    this.start = res.data.status.start;
                    this.end = res.data.status.end;
                    this.end2 = res.data.status.end;
                });
        },
        openModal(id) {
            this.dialog = true;
            axios.get("/api/get_all_spots/" + this.unitId).then((res) => {
                this.items = res.data.status.map((res) => ({
                    value: res.event_id,
                    text: res.ee_events_unit_spot,
                }));
                this.addId = id;
            });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                this.loading = true;
                axios
                    .post("/add_sub_inventory_ticket", {
                        id: this.addId,
                        event: this.eventName,
                        startingNumber: this.start,
                        endingNumber: this.end,
                    })
                    .then((res) => {
                        this.loading = false;
                        this.dialog = false;
                        this.$router.push({
                            path:
                                "/unit/branch/" +
                                this.unitId +
                                "/" +
                                this.unitName.replace(/ /g, "_") +
                                "/loading",
                            query: { where: "unit_ticket_inventory" },
                        });
                        this.$swal({
                            icon: "success",
                            title: "Added success",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    });
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        },
    },
};
</script>
