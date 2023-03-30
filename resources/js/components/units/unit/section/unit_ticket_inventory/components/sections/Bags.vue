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
                        <!-- <a href="#" @click="nextBag(i.id)">{{
                            i.ticket_bag
                        }}</a> -->
                        {{ i.ticket_bag }}
                    </th>
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
    }),

    methods: {
        nextBag() {
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/unit_ticket_inventory/bags",
                query: {
                    ticketid: String(id),
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
                        "/unit_ticket_inventory",
                });
            } else if (e.target.id === "2") {
                this.$router.push({
                    path:
                        "/unit/branch/" +
                        this.unitId +
                        "/" +
                        this.unitName.replace(/ /g, "_") +
                        "/unit_ticket_inventory/events",
                    query: {
                        ticketid: String(this.$route.query.ticketid),
                    },
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
                    title: "Events Ticket Inventoryss",
                    disabled: false,
                    id: 2,
                    href: "javascript:;",
                },
                {
                    title: "Event Bags Inventory",
                    disabled: false,
                    id: 3,
                },

                {
                    title: "",
                    disabled: false,
                },
            ];
            this.unitId = this.$route.path.split("/")[3];
            this.ticektId = this.$route.query.ticketid;
            this.subid = this.$route.query.subid;
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");

            const globalid = this.subid;
            const subid = this.ticektId;
            axios
                .post("/get_event_ticket_assigned_in_bag", {
                    unitId: this.unitId,
                    start: this.$route.query.start,
                    end: this.$route.query.end,
                    eventid: this.$route.query.eventid,
                    subid: globalid,
                })
                .then((res) => {
                    console.log(res.data.status);
                    this.showData = res.data.status;
                });
        },
    },
};
</script>
