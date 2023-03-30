<template>
    <div>
        <v-breadcrumbs class="text-capitalize" :items="items" @click="nextPage">
            <template v-slot:divider>
                <v-icon icon="mdi-chevron-right"></v-icon>
            </template>
        </v-breadcrumbs>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Bag Name</th>
                    <th scope="col">Seller Name</th>
                    <th scope="col">Ticket Type</th>
                    <th scope="col">Starting #</th>
                    <th scope="col">Ending #</th>
                    <th scope="col">Total</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Remaining</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in showData">
                    <th scope="row">
                        <v-btn
                            variant="flat"
                            size="small"
                            @click="
                                showBags(i.bag_id, i.seller_name, i.ticket_name)
                            "
                        >
                            <u class="text-blue"> {{ i.ticket_bag }}</u>
                        </v-btn>
                    </th>
                    <td>{{ i.seller_name }}</td>
                    <td>{{ i.ticket_name }}</td>
                    <td>{{ i.ticket_start }}</td>
                    <td>{{ i.ticket_end_number }}</td>
                    <td>{{ i.ticket_end_number - i.ticket_start + 1 }}</td>
                    <td>
                        {{
                            i.ticket_status === "Sold"
                                ? i.ticket_end_number - i.ticket_start + 1
                                : "0"
                        }}
                    </td>
                    <td>
                        {{
                            i.ticket_status !== "Sold"
                                ? i.ticket_end_number - i.ticket_start + 1
                                : "0"
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    mounted() {
        this.mount();
    },
    data: () => ({
        unitId: "",
        ticektId: "",
        unitName: "",
        eventName: "",
        showData: "",
        items: [],
        eventId: "",
        ticketName: "",
    }),

    methods: {
        showBags(bagid, sellerName, ticketName) {
            //  http://127.0.0.1:8000/unit/branch/7/NAME_Canada_Co./NAME_Canada_Co_Event_2022/event_bags/inside_bag/all_tickets?event_id=7&event_id=741&event_id=Zennon
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.unitName.replace(/ /g, "_") +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/event_bags/inside_bag/all_tickets",
                query: {
                    event_id: [
                        this.eventId,
                        String(bagid),
                        sellerName,
                        ticketName,
                    ],
                },
            });
        },

        nextPage(e) {
            if (e.target.id === "1") {
                this.$router.push({
                    path:
                        "/unit/branch/" +
                        this.unitId +
                        "/" +
                        this.unitName.replace(/ /g, "_") +
                        "/" +
                        this.eventName.replace(/ /g, "_") +
                        "/event_inventory",
                    query: { event_id: this.eventId },
                });
            }
            // else if (e.target.id === "2") {
            //      this.$router.push({
            //         path:
            //             "/unit/branch/" +
            //             this.unitId +
            //             "/" +
            //             this.unitName.replace(/ /g, "_") +
            //             "/" +
            //             this.eventName.replace(/ /g, "_") +
            //             "/event_inventory",
            //         query: { event_id: this.eventId },
            //     });
            // }
        },
        mount() {
            this.items = [
                {
                    title: "Event Ticket Inventory",
                    disabled: false,
                    id: 1,
                    href: "javascript:;",
                },
                {
                    title: "Event Bags Inventory",
                    disabled: false,
                    id: 2,
                },

                {
                    title: "",
                    disabled: false,
                },
            ];

            this.eventId = this.$route.query.event_id;

            this.unitId = this.$route.path.split("/")[3];
            this.ticektId = this.$route.query.event_id;
            this.subid = this.$route.query.subid;
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");

            const globalid = this.$route.query.subid;

            axios
                .get(
                    "/api/get_event_ticket_assigned_in_bag2/" +
                        [
                            this.unitId,
                            this.$route.query.start,
                            this.$route.query.end,
                            this.$route.query.event_id,
                            globalid,
                        ]
                )
                .then((res) => {
                    this.showData = res.data.status;
                    console.log(res.data.status);
                });
        },
    },
};
</script>
