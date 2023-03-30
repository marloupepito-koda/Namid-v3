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
        date: null,
        unitId: "",
        eventId: "",
        unitName: "",
        eventName: "",
        bagPath: "",
        bagId: "",
        sellerName: "",
    }),
    components: {
        Datepicker,
    },
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.date =
                this.$route.query.searchDate === undefined
                    ? "undefined"
                    : moment(this.$route.query.searchDate).format("LLL");
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.query.event_id[0];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5];
            this.bagId = this.$route.query.event_id[1];
            this.sellerName = this.$route.query.event_id[2];
        },
        searchDate(e) {
            const bagPath = this.$route.path.split("/")[7];
            const insidePath = this.$route.path.split("/")[8];
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName.replace(/ /g, "_") +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/event_bags/inside_bag/loading/",
                query: {
                    where: [
                        this.eventId,
                        String(this.bagId),
                        this.sellerName,
                        this.date,
                        insidePath,
                    ],
                },
            });
        },
    },
};
</script>
