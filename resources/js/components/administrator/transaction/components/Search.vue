<template>
    <v-form ref="form" v-model="valid" lazy-validation>
        <div class="row mt-5 ml-3">
            <div class="col-md-3 col-12">
                <v-select
                    @update:modelValue="unit"
                    v-model="unitName"
                    :items="items1"
                    :rules="unitNameRules"
                    item-title="text"
                    label="Unit Name"
                    variant="outlined"
                    density="compact"
                    required
                ></v-select>
            </div>
            <div class="col-md-4 col-6">
                <v-select
                    @update:modelValue="event"
                    v-model="eventName"
                    :items="items2"
                    :rules="eventNameRules"
                    item-title="text"
                    label="Event Name"
                    variant="outlined"
                    density="compact"
                    required
                ></v-select>
            </div>
            <div class="col-md-3 col-6">
                <Datepicker
                    :enable-time-picker="false"
                    class="mb-3"
                    placeholder="Select Date"
                    v-model="date"
                    :state="dateRules"
                    range
                    input-class-name="dp-custom-input"
                    min-range="3"
                ></Datepicker>
            </div>
            <div class="col-md-2 col-6">
                <v-btn
                    color="black"
                    prepend-icon="mdi-magnify"
                    class="mr-4"
                    @click="validate"
                    :loading="loading"
                >
                    Search
                </v-btn>
            </div>
        </div>
    </v-form>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import moment from "moment";
export default {
    components: { Datepicker },
    mounted() {
        this.mount();
    },
    data: () => ({
        items: [],
        unitId: "",
        addId: "",
        dialog: false,
        valid: true,
        unitName: null,
        unitNameRules: [(v) => !!v || "Unit Name is required"],
        eventName: null,
        eventNameRules: [(v) => !!v || "Event Name is required"],
        date: null,
        dateRules: null,
        items1: [],
        items2: [],
        loading: false,
    }),

    methods: {
        unit(e) {
            this.eventName = "";
            axios
                .get("/api/get_every_spot2/" + e)
                .then((res) => {
                    this.unitName = e;
                    this.items2 = res.data.status.map(
                        (a) => a.ee_events_unit_spot
                    );
                    let urls = ["/api/get_every_spot2/" + e];

                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                })
                .catch((err) => {
                    this.eventName = "";
                });
        },
        event(e) {
            this.eventName = e;
            axios
                .get("/api/get_every_spot3/" + [this.unitName, e])
                .then((res) => {
                    const unit_id = res.data.unit;
                    const spot_id = res.data.spot;
                    axios
                        .get("/api/get_ticket_sold/" + [unit_id, spot_id])
                        .then((result) => {
                            let urls = [
                                "/api/get_ticket_sold/" + [unit_id, spot_id],
                                "/api/get_every_spot3/" + [this.unitName, e],
                            ];

                            caches.open("static_cache").then((cache) => {
                                cache.addAll(urls).then(() => {
                                    console.log("Data cached ");
                                });
                            });
                            const startDate = new Date(
                                result.data.status.ee_events_unit_start_date
                            );
                            const endDate = new Date(
                                result.data.status.ee_events_unit_end_date
                            );
                            this.date = [startDate, endDate];
                        });
                })
                .catch((err) => {});
        },
        async validate() {
            this.loading = true;
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                const start = moment(this.date[0]).format("L");
                const end = moment(this.date[1]).format("L");
                if (this.date === null || this.date === "") {
                    this.dateRules = false;
                    this.loading = false;
                } else {
                    this.$router.push({
                        path: "/administrator/transaction/loading",
                        query: {
                            searchTransaction: [
                                this.unitName,
                                this.eventName,
                                start,
                                end,
                            ],
                        },
                    });
                    this.loading = false;
                }
            } else {
                this.loading = false;
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
        mount() {
            if (this.$route.query.searchTransaction !== undefined) {
                this.unitName = this.$route.query.searchTransaction[0];
                this.eventName = this.$route.query.searchTransaction[1];
                this.date = [
                    this.$route.query.searchTransaction[2],
                    this.$route.query.searchTransaction[3],
                ];
            }
            axios
                .get("/api/get_all_units")
                .then((res) => {
                    this.items1 = res.data.status.map(
                        (a) => a.ee_client_unit_name
                    );
                })
                .catch((err) => {});
            if (this.eventName !== null) {
                axios
                    .get("/api/get_every_spot2/" + this.unitName)
                    .then((res) => {
                        this.items2 = res.data.status.map(
                            (a) => a.ee_events_unit_spot
                        );

                        let urls = ["/api/get_every_spot2/" + this.unitName];

                        caches.open("static_cache").then((cache) => {
                            cache.addAll(urls).then(() => {
                                console.log("Data cached ");
                            });
                        });
                    })
                    .catch((err) => {});
            }
        },
    },
};
</script>
