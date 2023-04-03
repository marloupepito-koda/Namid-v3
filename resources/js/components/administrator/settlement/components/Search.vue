<template>
    <v-form ref="form" v-model="valid" lazy-validation>
        <div class="row mt-5 ml-3">
            <div class="col-md-3 col-12">
                <v-select
                    @update:modelValue="unit"
                    v-model="unitName"
                    :items="items1.map((res) => res.unit_name)"
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
        eventId: "",
    }),

    methods: {
        unit(e) {
            const id = this.items1.find((obj) => obj.unit_name === e);
            this.eventName = "";
            axios
                .get("/api/get_events_in_unit/" + id.id)
                .then((res) => {
                    this.unitName = e;
                    this.unitId = id.id;
                    this.items2 = res.data.status.map((a) => a.events_name);
                })
                .catch((err) => {
                    this.eventName = "";
                });
        },
        event(e) {
            this.eventName = e;
            axios
                .get("/api/get_every_events/" + this.unitId + "/" + e)
                .then((res) => {
                    const startDate = new Date(res.data.status.start);
                    const endDate = new Date(res.data.status.end);
                    this.date = [startDate, endDate];
                    this.unitId = res.data.status.unitid;
                    this.eventId = res.data.status.id;
                })
                .catch((err) => {});
        },
        async validate() {
            this.loading = true;
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                const start = moment(this.date[0]).format("L");
                const end = moment(this.date[1]).format("L");
                this.$router.push({
                    path: "/administrator/settlement",
                    query: {
                        unitid: this.unitId,
                        eventid: this.eventId,
                    },
                    hash: "#" + Math.floor(Math.random() * 999999),
                });
                this.loading = false;
            } else {
                this.loading = false;
            }
        },
        mount() {
            axios
                .get("/api/get_all_units")
                .then((res) => {
                    this.items1 = res.data.status.map((a) => ({
                        unit_name: a.units_name,
                        id: a.id,
                    }));
                })
                .catch((err) => {});
        },
    },
};
</script>
