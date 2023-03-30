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
                    <th scope="col">Event Name</th>
                    <th scope="col">Ticket Type</th>
                    <th scope="col">Starting #</th>
                    <th scope="col">Ending #</th>
                    <th scope="col">Total</th>
                    <!-- <th scope="col">Sold</th> -->
                    <th scope="col">Remaining</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="i in showData">
                    <th scope="row">
                        <v-btn
                            variant="link"
                            color="blue"
                            @click="
                                nextBag(
                                    i.id,
                                    i.ticket_start,
                                    i.ticket_end,
                                    i.ticket_foreign_spot,
                                    i.ticket_number
                                )
                            "
                            ><u>{{ i.ticket_event }}</u></v-btn
                        >
                    </th>
                    <td>{{ i.ticket_number }}</td>
                    <td>{{ i.ticket_start }}</td>
                    <td>{{ i.ticket_end }}</td>
                    <td>{{ i.ticket_end - i.ticket_start + 1 }}</td>
                    <td>{{ i.status }}</td>
                    <!-- <td v-if="i.sold !== null">
                        {{ i.sold }}
                    </td>
                    <td v-else>0</td> -->
                    <!-- <td v-if="i.sold !== null">
                        {{
                            parseInt(i.ticket_end) -
                            parseInt(i.ticket_start) +
                            1 -
                            parseInt(i.sold)
                        }}
                    </td>
                    <td v-else>
                        {{
                            parseInt(i.ticket_end) -
                            parseInt(i.ticket_start) +
                            1
                        }}
                    </td> -->
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
    }),

    methods: {
        nextBag(id, start, end, eventid, ticketType) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName.replace(/ /g, "_") +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/event_inventory?",
                query: { event_id: [eventid, ticketType] },
            });
            // this.$router.push({
            //     path:
            //         "/administrator/dashboard/" +
            //         this.unitId +
            //         "/" +
            //         this.unitName.replace(/ /g, "_") +
            //         "/unit_ticket_inventory/bags",
            //     query: {
            //         ticketid: String(this.$route.query.ticketid),
            //         subid: String(id),
            //         start: String(start),
            //         end: String(end),
            //         eventid: String(eventid),
            //     },
            // });
        },
        nextPage(e) {
            if (e.target.id === "1") {
                this.$router.push({
                    path:
                        "/administrator/dashboard/" +
                        this.unitId +
                        "/" +
                        this.eventName +
                        "/unit_ticket_inventory",
                });
            }
        },
        mount() {
            this.items = [
                {
                    title: "Unit Ticket Inventory",
                    disabled: false,
                    id: 1,
                    href: "javascript:;",
                },
                {
                    title: "Events Ticket Inventory",
                    disabled: false,
                    id: 2,
                },

                {
                    title: "",
                    disabled: false,
                },
            ];
            this.unitId = this.$route.path.split("/")[3];
            this.ticektId = this.$route.query.ticketid;
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");

            axios
                .post("/get_event_ticket_assigned", {
                    id: this.ticektId,
                })
                .then((res) => {
                    this.showData = Object.values(res.data.status);
                });
        },
    },
};
</script>
