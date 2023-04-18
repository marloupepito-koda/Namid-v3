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
                        <th class="text-left">Price</th>
                        <th class="text-left">ATP</th>
                        <th class="text-left">Quantity</th>
                        <th class="text-left">Amount</th>
                        <th class="text-left">Starting #</th>
                        <th class="text-left">Ending #</th>
                        <th class="text-left">Delete</th>
                        <th class="text-left">Add</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(i, index) in pendings" :key="i.name">
                        <td>
                            {{ i.data.ticket_type }}
                        </td>
                        <td>{{ i.data.price }}</td>

                        <td>{{ i.data.price / i.data.count }}</td>
                        <td>{{ i.end - i.start + 1 }}</td>
                        <td>
                            {{ (i.end - i.start + 1) * i.data.price }}
                        </td>
                        <td>{{ i.start }}</td>

                        <td>{{ i.end }}</td>
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
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.bagId = this.$route.path.split("/")[9];
        this.pendings = JSON.parse(
            localStorage.getItem(
                "create_ticket_sold" + this.unitId + this.eventId + this.bagId
            )
        );
        console.log(this.pendings);
        this.internet = localStorage.getItem("internet");
    },
    methods: {
        goBack() {
            this.$router.push({
                path:
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/inside_bag/all_tickets/" +
                    this.bagId,
            });
        },

        deletePendings(item) {
            this.pendings.splice(item, 1);
            localStorage.setItem(
                "create_ticket_sold" + this.unitId + this.eventId + this.bagId,
                JSON.stringify(this.pendings)
            );
        },
        addPendings(item, index) {
            if (localStorage.getItem("internet") === "offline") {
                // this.pendings = "offline";
                this.internet = "offline";
                this.snackbar = true;
            } else {
                axios.post("/create_ticket_sold", item).then((res) => {
                    const name = localStorage.getItem("name");

                    this.$swal({
                        icon: "success",
                        title: "Ticket Added",
                        showConfirmButton: false,
                        timer: 1000,
                    });
                    this.pendings.splice(index, 1);
                    localStorage.setItem(
                        "create_ticket_sold" +
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
