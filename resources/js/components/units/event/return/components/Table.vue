<template>
    <div>
        <center v-if="load === true" style="margin-top: 150px">
            <v-progress-circular
                indeterminate
                color="black"
            ></v-progress-circular>
        </center>
        <v-card v-else>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    label="Search"
                    hide-details
                    density="compact"
                    variant="outlined"
                    append-inner-icon="mdi-magnify"
                    @input="searchData"
                ></v-text-field>
            </v-card-title>
            <v-table density="compact">
                <thead>
                    <tr>
                        <th class="text-left">Bag Name</th>
                        <th class="text-left">Seller Name</th>
                        <th class="text-left">Number of Tickets</th>
                        <!-- <th class="text-left">Number of Seller</th> -->
                        <th class="text-left">Remaining Ticket in Bag</th>
                        <th class="text-left">Created At</th>
                        <th class="text-left">Return</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            <a
                                href="javascript:;"
                                @click="
                                    showTickets(i.id, i.seller_name, i.bag_name)
                                "
                                >{{ i.bag_name }}</a
                            >
                        </td>
                        <td>
                            <a
                                href="javascript:;"
                                @click="
                                    showTickets(i.id, i.seller_name, i.bag_name)
                                "
                                v-if="i.seller_name !== null"
                                >{{ i.seller_name }}</a
                            >
                            <v-chip
                                size="small"
                                v-else
                                color="red"
                                text-color="white"
                                >No Designated Seller</v-chip
                            >
                        </td>
                        <td>{{ i.n_o_t }}</td>
                        <!-- <td>{{ i.n_o_s }}</td> -->
                        <td>{{ i.remaining_ticket }}</td>
                        <td>{{ i.bag_date }}</td>
                        <td>
                            <a
                                href="javascript:;"
                                @click="returnTicket(i.id, i.bag_name)"
                            >
                                <v-icon size="large" color="blue">
                                    mdi-arrow-u-right-bottom-bold
                                </v-icon></a
                            >
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-card>
    </div>
</template>

<script>
export default {
    mounted() {
        this.mount();
    },
    methods: {
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
        showTickets(id, sellerName, bagName) {
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/returned_bags/inside_bag/all_tickets",
                query: {
                    event_id: [this.eventId, String(id), sellerName, bagName],
                },
            });
        },
        returnTicket(id, bagName) {
            this.$swal({
                title: "Are you sure?",
                text: "You want to return this bag into Event!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, return it!",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post("/return_bag", {
                            id: id,
                            status: null,
                            not: "back",
                        })
                        .then((res) => {
                            this.dialog = false;

                            const name = localStorage.getItem("name");
                            this.logs(
                                name +
                                    " Returned the " +
                                    bagName +
                                    " Event Bag Inventory."
                            );
                            this.$router.push({
                                path:
                                    "/unit/branch/" +
                                    this.unitId +
                                    "/" +
                                    this.unitName.replace(/ /g, "_") +
                                    "/" +
                                    this.eventName.replace(/ /g, "_") +
                                    "/loading",
                                query: {
                                    where: [
                                        "returned_bags",
                                        String(this.eventId),
                                    ],
                                },
                            });
                        });
                }
            });
        },
        searchData(e) {
            const keyword = this.search;
            if (e.inputType === "insertText") {
                const filtered = this.getData.filter((entry) =>
                    Object.values(entry).some(
                        (val) =>
                            typeof val === "string" &&
                            val.toLowerCase().includes(keyword.toLowerCase())
                    )
                );
                this.getData = filtered;
            } else {
                this.getData = this.getData2;
            }
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.query.event_id[0];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5];
            this.bagDestination = this.$route.path.split("/")[8];
            this.bagId = this.$route.query.event_id[1];
            this.sellerName = this.$route.query.event_id[2];
            this.bagsss = this.$route.query.event_id[3];
            axios
                .get("/api/get_return_bag/" + [this.unitId, "Returned"])
                .then((res) => {
                    let urls = [
                        "/api/get_return_bag/" + [this.unitId, "Returned"],
                    ];
                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                    this.getData = res.data.status;
                    this.getData2 = res.data.status;
                    this.load = false;
                });
        },
    },
    data() {
        return {
            bagName: "",
            load: true,
            unitId: "",
            eventName: "",
            search: [],
            getData: [],
            getData2: [],
        };
    },
};
</script>
