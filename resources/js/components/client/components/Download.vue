<template>
    <v-btn icon @click="downloadData()" :loading="loading">
        <v-tooltip
            text="Click to Download"
            activator="parent"
            location="bottom"
        >
            <template v-slot:activator="{ props }"> </template>
        </v-tooltip>
        <v-icon size="x-large">mdi-cloud-download-outline</v-icon>
    </v-btn>
</template>
<script>
import moment from "moment";
export default {
    data() {
        return {
            unitId: "",
            eventId: "",
            loading: false,
            urls: [],
            isDomContentLoaded: false,
        };
    },
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.unitId = this.$route.params.unitid;
        },
        loadingSwalAlert(status) {
            let timerInterval;
            this.$swal({
                toast: true,
                showConfirmButton: false,
                title: "Fetching ...",
                html: "<b></b>",
                timer: status === true ? false : 10000,
                timerProgressBar: true,
                didOpen: () => {
                    this.$swal.showLoading();
                    const b = this.$swal.getHtmlContainer().querySelector("b");
                    timerInterval = setInterval(() => {
                        b.textContent = Math.round(
                            this.$swal.getTimerLeft() / 1000
                        );
                    }, 1000);
                },
            }).then((result) => {
                if (result.dismiss === this.$swal.DismissReason.timer) {
                    this.loading = false;
                    this.$swal({
                        icon: "success",
                        title: "Downloading Completed!",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                }
            });
        },
        downloadData() {
            this.getEvents();
        },
        getEvents() {
            this.loadingSwalAlert(true);
            axios
                .get("/api/get_unit_ticket_inventory/" + this.unitId)
                .then((res) => {
                    res.data.status.map((aaa) =>
                        this.getEachTicketInUnit(aaa.id)
                    );
                });

            axios.get("/api/get_events_in_unit/" + this.unitId).then((res) => {
                this.unitPages();
                res.data.status.map((aaa) =>
                    this.getEventTickets(aaa.unitid, aaa.id)
                );
                res.data.status.map((aaa) =>
                    this.getEventAPI(aaa.unitid, aaa.id)
                );
                res.data.status.map((aaa) =>
                    this.eventPages(aaa.unitid, aaa.id)
                );
                res.data.status.map((aaa) =>
                    this.getEventBags(aaa.unitid, aaa.id)
                );
                caches.open("static_cache").then((cache) => {
                    cache.addAll(this.urls).then(() => {});
                });
            });
            this.loadingSwalAlert(false);
        },
        getEachTicketInUnit(ticketid) {
            const urls = ["/api/get_each_ticket_in_unit/" + ticketid];
            caches.open("static_cache").then((cache) => {
                cache.addAll(urls).then(() => {});
            });
        },
        getEventTickets(unitid, eventid) {
            axios
                .get("/api/get_ticket_inventory/" + unitid + "/" + eventid)
                .then((res) => {
                    res.data.status.map((aaa) =>
                        this.getEveryTicketInEvent(
                            aaa.unitid,
                            aaa.eventid,
                            aaa.id
                        )
                    );
                });
        },
        getEveryTicketInEvent(unitid, eventid, ticketid) {
            this.urls.push(
                "/api/get_each_ticket_in_event_inventory/" + ticketid
            );
            caches.open("static_cache").then((cache) => {
                cache.addAll(this.urls).then(() => {});
            });
        },
        getEventAPI(unitid, eventid) {
            this.urls.push(
                "/api/get_event_bags/" + unitid + "/" + eventid + "/all",
                "/api/get_ticket_inventory/" + unitid + "/" + eventid,
                "/api/get_unit_inventory/" + unitid + "/" + eventid,
                "/api/get_returned_bag/" + unitid + "/" + eventid + "/Returned",
                "/api/getEventSeller/" +
                    unitid +
                    "/" +
                    eventid +
                    "/" +
                    moment().format("MM-DD-YYYY"),
                "/api/get_event_ticket_sold_history/" + unitid + "/" + eventid
            );
            caches.open("static_cache").then((cache) => {
                cache.addAll(this.urls).then(() => {});
            });
        },
        getEventBags(unitid, eventid) {
            axios
                .get("/api/get_event_bags/" + unitid + "/" + eventid + "/all")
                .then((res) => {
                    res.data.status.map((aaa) =>
                        this.getEventBagPages(aaa.unitid, aaa.eventid, aaa.id)
                    );
                    res.data.status.map((aaa) =>
                        this.getTicketInBag(aaa.unitid, aaa.eventid, aaa.id)
                    );
                });
        },
        getTicketInBag(unitid, eventid, bagid) {
            axios
                .get(
                    "/api/get_tickets_in_bag/" +
                        unitid +
                        "/" +
                        eventid +
                        "/" +
                        bagid +
                        "/all"
                )
                .then((result) => {
                    localStorage.setItem(
                        "get_tickets_in_bag" + unitid + eventid + bagid,
                        JSON.stringify(result.data.status)
                    );
                });
            this.urls.push(
                "/api/get_tickets_in_bag/" +
                    unitid +
                    "/" +
                    eventid +
                    "/" +
                    String(bagid) +
                    "/all",
                "/api/get_tickets_in_bag/" +
                    unitid +
                    "/" +
                    eventid +
                    "/" +
                    String(bagid) +
                    "/sold",
                "/api/get_tickets_in_bag/" +
                    unitid +
                    "/" +
                    eventid +
                    "/" +
                    String(bagid) +
                    "/unsold"
            );
            caches.open("static_cache").then((cache) => {
                cache.addAll(this.urls).then(() => {});
            });
        },
        getEventBagPages(unitid, eventid, bagid) {
            this.urls.push(
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/event_bags/inside_bag/all_tickets/" +
                    bagid,
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/event_bags/inside_bag/sold_tickets/" +
                    bagid,
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/event_bags/inside_bag/unsold_tickets/" +
                    bagid
            );
            caches.open("static_cache").then((cache) => {
                cache.addAll(this.urls).then(() => {});
            });
        },
        unitPages() {
            const urls = [
                "/client/branch/" + this.unitId + "/event_list",
                "/client/branch/" + this.unitId + "/unit_ticket_inventory",
                "/api/get_unit_ticket_inventory/" + this.unitId + "",
                "/api/get_events_in_unit/" + this.unitId,
            ];

            caches.open("static_cache").then((cache) => {
                cache.addAll(urls).then(() => {});
            });
        },
        eventPages(unitid, eventid) {
            this.urls.push(
                "/client/branch/" + unitid + "/" + eventid + "/event_inventory",
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/unit_ticket_inventory",
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/event_bags/all_bags",
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/event_bags/active_bags",
                "/client/branch/" +
                    unitid +
                    "/" +
                    eventid +
                    "/event_bags/not_active_bags",
                "/client/branch/" + unitid + "/" + eventid + "/returned_bags",
                "/client/branch/" + unitid + "/" + eventid + "/daily_breakdown",
                "/client/branch/" + unitid + "/" + eventid + "/logs",
                "/client/branch/" + unitid + "/" + eventid + "/event_history"
            );
            caches.open("static_cache").then((cache) => {
                cache.addAll(this.urls).then(() => {});
            });
        },
    },
};
</script>
