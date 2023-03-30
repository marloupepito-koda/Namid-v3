<template>
    <div class="container mt-3">
        <v-btn
            :loading="loading"
            variant="outlined"
            color="black"
            @click="upload"
            >Upload Latest Data</v-btn
        >
    </div>
</template>

<script>
import moment from "moment";
export default {
    data() {
        return {
            fullPage: false,
        };
    },
    methods: {
        upload() {
            this.unitId = this.$route.path.split("/")[3];
            axios.get("/api/get_all_spots/" + this.unitId).then((res) => {
                for (let index = 0; index < res.data.status.length; index++) {
                    const event = res.data.status[index];
                    const u_id = event.foreign_id;
                    const e_id = event.event_id;
                    const unitTickets = JSON.parse(
                        localStorage.getItem("ee_add_ticket" + u_id + e_id)
                    );

                    async function ee_add_ticket() {
                        try {
                            for (
                                let index = 0;
                                index < unitTickets.length;
                                index++
                            ) {
                                const pendingTickets = unitTickets[index];
                                axios
                                    .post("/ee_add_ticket", pendingTickets)
                                    .then((ress) => {
                                        return ress.data.status;
                                    });
                            }
                        } catch (error) {
                            console.log(error);
                        }
                    }
                    ee_add_ticket().then((wait) => {
                        axios
                            .get(
                                "/api/get_event_inventory_bags/" +
                                    [20, "undefined", u_id, e_id, "All"]
                            )
                            .then((res) => {
                                for (
                                    let index = 0;
                                    index < res.data.status.data.length;
                                    index++
                                ) {
                                    const bag = res.data.status.data[index];

                                    const ticketPendingsList = JSON.parse(
                                        localStorage.getItem(
                                            "add_ticket_bag" +
                                                u_id +
                                                e_id +
                                                bag.id
                                        )
                                    );
                                    if (ticketPendingsList !== null) {
                                        axios
                                            .get(
                                                "/api/get_all_ticket3/" +
                                                    [u_id, e_id]
                                            )
                                            .then((res) => {
                                                const ticketList =
                                                    res.data.status;

                                                for (
                                                    let index = 0;
                                                    index <
                                                    ticketPendingsList.length;
                                                    index++
                                                ) {
                                                    if (
                                                        ticketPendingsList[
                                                            index
                                                        ].random === ""
                                                    ) {
                                                        axios
                                                            .post(
                                                                "/add_ticket_bag",
                                                                ticketPendingsList[
                                                                    index
                                                                ]
                                                            )
                                                            .then((res) => {
                                                                console.log(
                                                                    "this upload online"
                                                                );
                                                            });
                                                    } else {
                                                        console.log(
                                                            "bwahahahhahaha",
                                                            ticketPendingsList[
                                                                index
                                                            ]
                                                        );
                                                        axios
                                                            .post(
                                                                "/add_ticket_bag2",
                                                                ticketPendingsList[
                                                                    index
                                                                ]
                                                            )
                                                            .then((res) => {
                                                                console.log(
                                                                    "this upload offline"
                                                                );
                                                            });
                                                    }
                                                }

                                                const pendingSoldTickets =
                                                    JSON.parse(
                                                        localStorage.getItem(
                                                            "add_ticket_sold" +
                                                                u_id +
                                                                e_id +
                                                                bag.id
                                                        )
                                                    );
                                                if (
                                                    pendingSoldTickets !== null
                                                ) {
                                                    axios
                                                        .put(
                                                            "/add_ticket_sold2",
                                                            {
                                                                data: pendingSoldTickets,
                                                            }
                                                        )
                                                        .then((res) => {
                                                            console.log(
                                                                "hello world",
                                                                pendingSoldTickets
                                                            );
                                                            // localStorage.removeItem(
                                                            //     "add_ticket_sold" +
                                                            //         u_id +
                                                            //         e_id +
                                                            //         bag.id
                                                            // );
                                                            // localStorage.removeItem(
                                                            //     "add_ticket_bag" +
                                                            //         u_id +
                                                            //         e_id +
                                                            //         bag.id
                                                            // );
                                                            // localStorage.removeItem(
                                                            //     "ee_add_ticket" +
                                                            //         u_id +
                                                            //         e_id
                                                            // );
                                                            //window.location.reload();
                                                        });
                                                }
                                            });
                                    }
                                }
                            });
                    });
                }
            });
        },
    },
};
</script>
