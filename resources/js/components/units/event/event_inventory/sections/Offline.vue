<template>
    <div class="row">
        <div class="col-md-1">
            <v-btn variant="flat" class="ml-1" @click="goBack"
                ><u class="text-blue h4">Back</u></v-btn
            >
        </div>
        <div class="col-md-11 h4">Offline Pendings List</div>
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-left">Ticket Type</th>
                        <th class="text-left">Ticket Name</th>
                        <th class="text-left">Starting #</th>
                        <th class="text-left">Ending #</th>
                        <th class="text-left">Bind</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Count</th>
                        <th class="text-left">ATP</th>
                        <th class="text-left">Remaining</th>
                        <th class="text-center">Delete</th>
                        <!-- <th class="text-center">Add Tickets</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in pendings" :key="i.name">
                        <td>
                            {{ i.tickettypenumber }}
                        </td>
                        <td>{{ i.ticketname }}</td>
                        <td>{{ i.start }}</td>
                        <td>{{ i.end }}</td>

                        <td>{{ i.bind_name }}</td>
                        <td>{{ i.ticketprice }}</td>
                        <td>{{ i.ticketcount }}</td>
                        <td>{{ i.ticketprice / i.ticketcount }}</td>

                        <td>{{ i.end - i.start + 1 }}</td>
                        <td>
                            <a href="#" @click="deletePendings(index)"
                                >delete</a
                            >
                        </td>
                        <!-- <td>
                            <a href="#" @click="addPendings(i, index)">Add</a>
                        </td> -->
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
        this.eventId =
            Array.isArray(this.$route.query.event_id) === true
                ? this.$route.query.event_id[0]
                : this.$route.query.event_id;
        this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");
        this.pendings = JSON.parse(
            localStorage.getItem("ee_add_ticket" + this.unitId + this.eventId)
        );
        this.internet = localStorage.getItem("internet");
        // localStorage.removeItem("ee_add_ticket");
    },
    methods: {
        goBack() {
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
            if (confirm("Do you want to delete this tickets?")) {
                this.pendings.splice(item, 1);
                localStorage.setItem(
                    "ee_add_ticket" + this.unitId + this.eventId,
                    JSON.stringify(this.pendings)
                );
            }
        },
        addPendings(item, index) {
            if (localStorage.getItem("internet") === "offline") {
                // this.pendings = "offline";
                this.internet = "offline";
                this.snackbar = true;
            } else {
                axios.post("/ee_add_ticket", item).then((res) => {
                    const name = localStorage.getItem("name");
                    this.logs(
                        name +
                            " added " +
                            item.ticketName +
                            " from starting # " +
                            item.start +
                            " to " +
                            item.end +
                            " and the total of " +
                            (item.end - item.start + 1) +
                            " Tickets " +
                            "with the amount of " +
                            item.price +
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
                        "ee_add_ticket" + this.unitId + this.eventId,
                        JSON.stringify(this.pendings)
                    );
                });
            }
        },
    },
};
</script>
