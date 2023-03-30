<template>
    <div class="container mt-3">
        <v-btn
            :loading="loading"
            variant="outlined"
            color="black"
            @click="download"
            >Download Latest Data</v-btn
        >
    </div>
</template>
<script>
export default {
    data() {
        return {
            fullPage: false,
            loading: false,
        };
    },
    mounted() {},
    methods: {
        download() {
            // simulate AJAX
            // setTimeout(() => {
            //
            // }, 5000);
            this.mount();
        },
        onCancel() {
            console.log("User cancelled the loader.");
        },
        mount() {
            this.loading = true;
            // let loader = this.$loading.show({
            //     loader: "bars",
            //     container: this.fullPage ? null : this.$refs.formContainer,
            //     canCancel: false,
            //     onCancel: this.onCancel,
            // });
            this.unitId = this.$route.path.split("/")[3];
            axios.get("/api/get_all_spots/" + this.unitId).then((res) => {
                let urls = ["/api/get_all_spots/" + this.unitId];
                caches.open("static_cache").then((cache) => {
                    cache.addAll(urls).then(() => {});
                });
                //fetching the cache in all events
                for (let i = 0; i < res.data.status.length; i++) {
                    const event = res.data.status[i];
                    let webpages = [
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/unit_ticket_inventory",
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/event_inventory?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/event_bags/all_bags?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/event_bags/active_bags?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/event_bags/not_active_bags?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/returned_bags?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/event_history?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/show_bags?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/event_inventory/offline?event_id=" +
                            event.event_id,
                        "/unit/branch/" +
                            event.foreign_id +
                            "/" +
                            event.ee_events_unit_name.replace(/ /g, "_") +
                            "/" +
                            event.ee_events_unit_spot.replace(/ /g, "_") +
                            "/logs?event_id=" +
                            event.event_id,
                    ];

                    caches.open("static_cache").then((cache) => {
                        cache.addAll(webpages).then(() => {});
                    });
                    axios
                        .get(
                            "/api/get_all_ticket3/" +
                                [this.unitId, event.event_id]
                        )
                        .then((ress) => {
                            let urls = [
                                "/api/get_all_ticket3/" +
                                    [this.unitId, event.event_id],
                            ];
                            for (
                                let ii = 0;
                                ii < ress.data.status.length;
                                ii++
                            ) {
                                const element = ress.data.status[ii];
                                const pendings = JSON.parse(
                                    localStorage.getItem(
                                        "ee_add_ticket" +
                                            this.unitId +
                                            event.event_id
                                    )
                                );
                                const max = Math.max.apply(
                                    Math,
                                    ress.data.status.map((zzzzzz) =>
                                        parseInt(zzzzzz.ee_ticket_id)
                                    )
                                );

                                if (pendings !== null) {
                                    const pendingMap = pendings.map(
                                        (res, index) => res.globalTicketId
                                    );

                                    for (
                                        let i = 0;
                                        i < pendingMap.length;
                                        i++
                                    ) {
                                        // const value = pendingMap[i];

                                        let mapPendings = [
                                            "/api/get_all_ticket2/" +
                                                [
                                                    this.unitId,
                                                    event.event_id,
                                                    parseInt(max) + i + 1,
                                                ],
                                        ];

                                        caches
                                            .open("static_cache")
                                            .then((cache) => {
                                                cache
                                                    .addAll(mapPendings)
                                                    .then(() => {});
                                            });
                                    }
                                }

                                let urlsa = [
                                    "/api/get_all_ticket2/" +
                                        [
                                            this.unitId,
                                            event.event_id,
                                            parseInt(element.ee_ticket_id),
                                        ],
                                ];
                                caches.open("static_cache").then((cache) => {
                                    cache.addAll(urlsa).then(() => {});
                                });
                            }

                            caches.open("static_cache").then((cache) => {
                                cache.addAll(urls).then(() => {});
                            });
                        });

                    // get all ticket in event

                    axios
                        .get(
                            "/api/get_all_ticket/" +
                                [this.unitId, event.event_id]
                        )
                        .then((resc) => {
                            let urlsb = [
                                "/api/get_all_ticket/" +
                                    [this.unitId, event.event_id],
                            ];

                            for (
                                let ii = 0;
                                ii < resc.data.status.length;
                                ii++
                            ) {
                                const element = resc.data.status[ii];
                                let urls22 = [
                                    "/api/get_event_ticket_assigned_in_bag2/" +
                                        [
                                            this.unitId,
                                            element.ticket_start,
                                            element.ticket_end,
                                            event.event_id,
                                            element.global_ticket_id,
                                        ],
                                ];
                                caches.open("static_cache").then((cache) => {
                                    cache.addAll(urls22).then(() => {});
                                });
                            }
                            caches.open("static_cache").then((cache) => {
                                cache.addAll(urlsb).then(() => {});
                            });
                        });

                    //end of get all ticket in event

                    //start of Global Ticket
                    axios
                        .get(
                            "/api/ee_get_all_global_ticket/" +
                                this.unitId +
                                "," +
                                event.ee_events_unit_spot.replace(/ /g, "_")
                        )
                        .then((res1) => {
                            let urls1 = [
                                "/api/ee_get_all_global_ticket/" +
                                    this.unitId +
                                    "," +
                                    event.ee_events_unit_spot.replace(
                                        / /g,
                                        "_"
                                    ),
                                "/api/ee_get_all_global_ticket2/" +
                                    this.unitId +
                                    "," +
                                    event.ee_events_unit_spot.replace(
                                        / /g,
                                        "_"
                                    ),
                            ];
                            console.log(urls1);
                            caches.open("static_cache").then((cache) => {
                                cache.addAll(urls1).then(() => {});
                            });
                            //start of every Global Ticket
                            for (
                                let ii = 0;
                                ii < res1.data.status.length;
                                ii++
                            ) {
                                const ticket = res1.data.status[ii];
                                let urls2 = [
                                    "/api/get_every_global_ticket/" +
                                        [
                                            ticket.ee_ticket_id,
                                            this.unitId,
                                            event.event_id,
                                        ],
                                ];
                                caches.open("static_cache").then((cache) => {
                                    cache.addAll(urls2).then(() => {});
                                });
                            }

                            let urlsReturnBags = [
                                "/api/get_return_bag/" +
                                    [this.unitId, "Returned"],
                                "/api/get_event_ticket_history/" +
                                    [this.unitId, event.event_id],
                            ];
                            caches.open("static_cache").then((cache) => {
                                cache.addAll(urlsReturnBags).then(() => {});
                            });
                            //start of All bags
                            axios
                                .get(
                                    "/api/get_event_inventory_bags/" +
                                        [
                                            20,
                                            "undefined",
                                            this.unitId,
                                            event.event_id,
                                            "All",
                                        ]
                                )
                                .then((res3) => {
                                    let urls3 = [
                                        "/api/get_event_inventory_bags/" +
                                            [
                                                20,
                                                "undefined",
                                                this.unitId,
                                                event.event_id,
                                                "All",
                                            ],
                                        "/api/get_event_inventory_bags/" +
                                            [
                                                20,
                                                "undefined",
                                                this.unitId,
                                                event.event_id,
                                                "Active",
                                            ],
                                        "/api/get_event_inventory_bags/" +
                                            [
                                                20,
                                                "undefined",
                                                this.unitId,
                                                event.event_id,
                                                "Not Active",
                                            ],
                                    ];
                                    caches
                                        .open("static_cache")
                                        .then((cache) => {
                                            cache.addAll(urls3).then(() => {});
                                        });
                                    for (
                                        let ii = 0;
                                        ii < res3.data.status.data.length;
                                        ii++
                                    ) {
                                        const bag = res3.data.status.data[ii];
                                        const seller =
                                            bag.seller_name === null
                                                ? ""
                                                : "=" + bag.seller_name;
                                        let webpages2 = [
                                            "/unit/branch/" +
                                                event.foreign_id +
                                                "/" +
                                                event.ee_events_unit_name.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/" +
                                                event.ee_events_unit_spot.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/event_bags/inside_bag/all_tickets?event_id=" +
                                                event.event_id +
                                                "&" +
                                                "event_id=" +
                                                bag.id +
                                                "&" +
                                                "event_id" +
                                                seller +
                                                "&" +
                                                "event_id=" +
                                                bag.bag_name.replace(/ /g, "+"),
                                            "/unit/branch/" +
                                                event.foreign_id +
                                                "/" +
                                                event.ee_events_unit_name.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/" +
                                                event.ee_events_unit_spot.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/event_bags/inside_bag/sold_tickets?event_id=" +
                                                event.event_id +
                                                "&" +
                                                "event_id=" +
                                                bag.id +
                                                "&" +
                                                "event_id" +
                                                seller +
                                                "&" +
                                                "event_id=" +
                                                bag.bag_name.replace(/ /g, "+"),
                                            "/unit/branch/" +
                                                event.foreign_id +
                                                "/" +
                                                event.ee_events_unit_name.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/" +
                                                event.ee_events_unit_spot.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/event_bags/inside_bag/unsold_tickets?event_id=" +
                                                event.event_id +
                                                "&" +
                                                "event_id=" +
                                                bag.id +
                                                "&" +
                                                "event_id" +
                                                seller +
                                                "&" +
                                                "event_id=" +
                                                bag.bag_name.replace(/ /g, "+"),
                                            "/unit/branch/" +
                                                event.foreign_id +
                                                "/" +
                                                event.ee_events_unit_name.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/" +
                                                event.ee_events_unit_spot.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/event_bags/inside_bag/all_tickets/offline?event_id=" +
                                                event.event_id +
                                                "&" +
                                                "event_id=" +
                                                bag.id +
                                                "&" +
                                                "event_id" +
                                                seller +
                                                "&" +
                                                "event_id=" +
                                                bag.bag_name.replace(/ /g, "+"),
                                            "/unit/branch/" +
                                                event.foreign_id +
                                                "/" +
                                                event.ee_events_unit_name.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/" +
                                                event.ee_events_unit_spot.replace(
                                                    / /g,
                                                    "_"
                                                ) +
                                                "/event_bags/inside_bag/all_tickets/offline2?event_id=" +
                                                event.event_id +
                                                "&" +
                                                "event_id=" +
                                                bag.id +
                                                "&" +
                                                "event_id" +
                                                seller +
                                                "&" +
                                                "event_id=" +
                                                bag.bag_name.replace(/ /g, "+"),
                                        ];
                                        console.log(webpages2);
                                        caches
                                            .open("static_cache")
                                            .then((cache) => {
                                                cache
                                                    .addAll(webpages2)
                                                    .then(() => {});
                                            });
                                        // start fetch all ticket inside the bag
                                        let urls4 = [
                                            "/api/get_event_bags/" +
                                                [
                                                    "undefined",
                                                    this.unitId,
                                                    event.event_id,
                                                    bag.id,
                                                ],
                                        ];
                                        caches
                                            .open("static_cache")
                                            .then((cache) => {
                                                cache
                                                    .addAll(urls4)
                                                    .then(() => {});
                                            });
                                        this.loading = false;
                                        // loader.hide();ss
                                        // end fetch all ticket inside the bagss
                                    }
                                });
                            //end of All bags
                        });
                    //end of Global Ticket
                }
                //end of fetching the cache in all events
            });
        },
    },
};
</script>
