<template>
    <div class="row">
        <div class="col-md-1">
            <v-btn variant="flat" class="ml-1" @click="goBack"
                ><u class="text-blue h4">Back</u></v-btn
            >
        </div>
        <div class="col-md-11 h4">Pending Sold Tickets Offline</div>
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left">Ticket Type</th>
                        <th class="text-left">Bag Name</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">ATP</th>
                        <th class="text-left">Quantity</th>
                        <th class="text-left">Amount</th>
                        <th class="text-left">Starting #</th>
                        <th class="text-left">Ending #</th>
                        <th class="text-center">Delete</th>
                        <th class="text-center">Add</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in pendings" :key="i.name">
                        <td>
                            {{ i.ticket_name }}
                        </td>
                        <td>{{ i.bagname }}</td>
                        <td>{{ i.ticket_price }}</td>

                        <td>{{ i.atp }}</td>
                        <td>{{ i.ticket_end - i.ticket_start + 1 }}</td>
                        <td>
                            {{
                                (i.ticket_end - i.ticket_start + 1) *
                                i.ticket_price
                            }}
                        </td>
                        <td>{{ i.ticket_start }}</td>

                        <td>{{ i.ticket_end }}</td>
                        <td>
                            <a href="#" @click="deletePendings(index)"
                                >delete</a
                            >
                        </td>
                        <td>
                            <a href="#" @click="addPendings(i, index)">Add</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <v-snackbar
            v-if="this.internet === 'offline'"
            location="top right"
            :timeout="2000"
            color="danger"
            v-model="snackbar"
            style="margin-top: 45px"
        >
            <v-icon end icon="mdi-wifi-off"></v-icon>
            Offline Internet Connection
        </v-snackbar>
    </div>
</template>

<script>
export default {
    data: () => ({
        snackbar: false,
        pendings: [],
        internet: "",
        unitId: "",
        eventId: "",
        eventName: "",
        unitName: "",
    }),
    mounted() {
        this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.query.event_id[0];
        this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");
        this.bagId = this.$route.query.event_id[1];
        this.sellerName = this.$route.query.event_id[2];
        this.bagName = this.$route.query.event_id[3];
        this.pendings = JSON.parse(
            localStorage.getItem(
                "add_ticket_sold" + this.unitId + this.eventId + this.bagId
            )
        );
        console.log(this.pendings);
        this.internet = localStorage.getItem("internet");
        // console.log("wwaa", this.pendings);
        // localStorage.removeItem("add_ticket_bag" + this.unitId + this.eventId);
    },
    methods: {
        goBack() {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName.replace(/ /g, "_") +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/event_bags/inside_bag/all_tickets",
                query: {
                    event_id: [
                        this.eventId,
                        this.bagId,
                        this.sellerName,
                        this.bagName,
                    ],
                },
            });
        },
        logs(event) {
            axios
                .post("/add_logs", {
                    unit_id: this.unitId,
                    event_id: this.eventId,
                    ticket_id: "",
                    descriptions: event,
                })
                .then((res) => {});
        },
        deletePendings(item) {
            this.pendings.splice(item, 1);
            localStorage.setItem(
                "add_ticket_sold" + this.unitId + this.eventId + this.bagId,
                JSON.stringify(this.pendings)
            );
        },
        addPendings(item, index) {
            if (localStorage.getItem("internet") === "offline") {
                // this.pendings = "offline";
                this.internet = "offline";
                this.snackbar = true;
            } else {
                axios.post("/add_ticket_sold", item).then((res) => {
                    const name = localStorage.getItem("name");
                    this.logs(
                        name +
                            " Mark Sold " +
                            item.ticket_name +
                            " from starting # " +
                            item.ticket_start +
                            " to " +
                            item.ticket_end +
                            " and the total of " +
                            (item.ticket_end - item.ticket_start + 1) +
                            " Tickets " +
                            "with the amount of " +
                            item.ticket_price +
                            " while offline mode"
                    );
                    this.$swal({
                        icon: "success",
                        title: "Ticket Added",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    this.pendings.splice(index, 1);
                    localStorage.setItem(
                        "add_ticket_sold" +
                            this.unitId +
                            this.eventId +
                            this.bagId,
                        JSON.stringify(this.pendings)
                    );
                });
            }
        },
    },
};
</script>
