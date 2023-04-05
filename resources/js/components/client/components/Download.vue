<template>
    <v-btn icon @click="webpages()" :loading="loading">
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
export default {
    data() {
        return {
            unitId: "",
            eventId: "",
            loading: false,
        };
    },
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.unitId = this.$route.params.id;
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
                        title: "Download Completed!",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });
        },
        getRestAPI(data, bag) {
            let urls = [];
            urls.push(
                ...data.map(
                    (ress) =>
                        "/api/get_ticket_inventory/" +
                        ress.unitid +
                        "/" +
                        ress.id
                ),
                ...data.map(
                    (ress) =>
                        "/api/get_event_bags/" +
                        ress.unitid +
                        "/" +
                        ress.id +
                        "/all"
                ),
                ...data.map(
                    (ress) =>
                        "/api/get_event_bags/" +
                        ress.unitid +
                        "/" +
                        ress.id +
                        "/active"
                ),
                ...data.map(
                    (ress) =>
                        "/api/get_event_bags/" +
                        ress.unitid +
                        "/" +
                        ress.id +
                        "/notactive"
                ),
                ...data.map(
                    (ress) =>
                        "/api/get_returned_bag/" +
                        ress.unitid +
                        "/" +
                        ress.id +
                        "/Returned"
                ),
                ...data.map(
                    (ress) =>
                        "/api/get_event_ticket_sold_history/" +
                        ress.unitid +
                        "/" +
                        ress.id
                )
            );

            bag.map((a) =>
                axios
                    .get(
                        "/api/get_event_bags/" +
                            a.unitid +
                            "/" +
                            String(a.eventid) +
                            "/all"
                    )
                    .then((aa) => {
                        urls.push(
                            ...aa.data.status.map(
                                (bb) =>
                                    "/api/get_tickets_in_bag/" +
                                    bb.unitid +
                                    "/" +
                                    bb.eventid +
                                    "/" +
                                    String(bb.id) +
                                    "/all"
                            ),
                            ...aa.data.status.map(
                                (bb) =>
                                    "/api/get_tickets_in_bag/" +
                                    bb.unitid +
                                    "/" +
                                    bb.eventid +
                                    "/" +
                                    String(bb.id) +
                                    "/sold"
                            ),
                            ...aa.data.status.map(
                                (bb) =>
                                    "/api/get_tickets_in_bag/" +
                                    bb.unitid +
                                    "/" +
                                    bb.eventid +
                                    "/" +
                                    String(bb.id) +
                                    "/unsold"
                            )
                        );
                        const finish = caches
                            .open("static_cache")
                            .then((cache) => {
                                cache.addAll(urls).then(() => {});
                            });
                        if (finish) {
                            this.loadingSwalAlert(false);
                        }
                    })
            );
        },
        webpages() {
            this.loading = true;
            this.loadingSwalAlert(true);
            let urls = [
                "/client/branch/" + this.unitId + "/event_list",
                "/client/branch/" + this.unitId + "/unit_ticket_inventory",
                "/client/branch/" + this.unitId + "/daily_breakdown",
                "/api/get_events_in_unit/" + this.unitId,
                "/api/get_unit_ticket_inventory/" + this.unitId,
            ];

            axios.get("/api/get_events_in_unit/" + this.unitId).then((res) => {
                urls.push(
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/event_inventory"
                    ),
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/event_bags/all_bags"
                    ),
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/event_bags/active_bags"
                    ),
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/event_bags/not_active_bags"
                    ),
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/returned_bags"
                    ),
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/logs"
                    ),
                    ...res.data.status.map(
                        (ress) =>
                            "/client/branch/" +
                            ress.unitid +
                            "/" +
                            ress.id +
                            "/event_history"
                    )
                );
                res.data.status.map((aa) =>
                    axios
                        .get(
                            "/api/get_event_bags/" +
                                aa.unitid +
                                "/" +
                                aa.id +
                                "/all"
                        )
                        .then((aaa) => {
                            urls.push(
                                ...aaa.data.status.map(
                                    (b) =>
                                        "/client/branch/" +
                                        b.unitid +
                                        "/" +
                                        b.eventid +
                                        "/event_bags/inside_bag/all_tickets/" +
                                        b.id
                                )
                            );

                            this.getRestAPI(res.data.status, aaa.data.status);
                        })
                );
                caches.open("static_cache").then((cache) => {
                    cache.addAll(urls).then(() => {});
                });
            });
        },
    },
};
</script>
