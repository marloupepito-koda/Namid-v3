<template>
    <div class="row mb-5">
        <div class="col-md-7 m-0">
            <Datepicker
                v-model="date"
                placeholder="Select Date"
                input-class-name="dp-custom-input"
            />
        </div>
        <div class="col-md-5 m-0">
            <v-btn
                prepend-icon="mdi-table-search"
                class="text-white"
                color="black"
                @click="searchDate"
            >
                Search</v-btn
            >
        </div>
    </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import moment from "moment";
export default {
    data: () => ({
        date: moment().format("LLL"),
        unitId: "",
        eventId: "",
        unitName: "",
        eventName: "",
        bagPath: "",
    }),
    components: {
        Datepicker,
    },
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.date = moment(this.$route.query.searchDate).format("LLL");
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.query.event_id;
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5];
        },
        searchDate(e) {
            const bagPath = this.$route.path.split("/")[7];
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.unitName.replace(/ /g, "_") +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/loading",
                query: {
                    where: ["event_bags/" + bagPath, String(this.eventId)],
                    searchDate: this.date,
                },
            });
        },
    },
};
</script>
