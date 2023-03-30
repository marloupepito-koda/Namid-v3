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
                        <th class="text-left">Name of Seller</th>
                        <th class="text-left">Location</th>
                        <th class="text-left">Remaining</th>
                        <th class="text-left">Created At</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Return</th>
                        <th class="text-center">Edit</th>
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
                        <td>
                            <a v-if="i.location !== null">{{ i.location }}</a>
                            <v-chip
                                size="small"
                                v-else
                                color="red"
                                text-color="white"
                                >None location</v-chip
                            >
                        </td>
                        <td>
                            {{
                                i.remaining_ticket === null
                                    ? 0
                                    : i.remaining_ticket.replace(
                                          /(\d)(?=(\d\d\d)+(?!\d))/g,
                                          "$1,"
                                      )
                            }}
                        </td>

                        <td>{{ i.bag_date }}</td>
                        <td class="text-center">
                            <v-chip
                                size="small"
                                v-if="
                                    i.remaining_ticket === null ||
                                    i.remaining_ticket === '0' ||
                                    i.n_o_r === null ||
                                    i.n_o_r === 0
                                "
                                color="red"
                                text-color="white"
                            >
                                Not Active
                            </v-chip>
                            <v-chip
                                size="small"
                                v-else
                                class="ma-2"
                                color="green"
                                text-color="white"
                            >
                                Active
                            </v-chip>
                        </td>

                        <td class="text-center">
                            <v-btn
                                size="small"
                                variant="outlined"
                                large
                                color="blue"
                                @click="
                                    returnBag(i.id, i.seller_name, i.bag_name)
                                "
                            >
                                <v-icon size="large" color="blue">
                                    mdi-arrow-down-left-bold</v-icon
                                >
                            </v-btn>
                        </td>
                        <td class="text-center">
                            <EditModal
                                :editId="i.id"
                                :editSellerName="i.seller_name"
                                :editBagName="i.bag_name"
                                :editLocation="i.location"
                            />
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-card>
    </div>
</template>

<script>
import moment from "moment";
import Modal from "./Modal.vue";
import EditModal from "./../../../components/EditModal.vue";
export default {
    mounted() {
        this.mount();
    },
    components: {
        Modal,
        EditModal,
    },
    data: () => ({
        load: true,
        getData: [],
        getData2: [],
        search: [],
        unitId: "",
        eventId: "",
        unitName: "",
        eventName: "",
        tickets: [],
        ticketList: [],
        currentPage: 1,
        currentPerPage: 20,
        date: "",
        bags: [],
    }),
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
        returnBag(id, sellerName, bagName) {
            this.$swal({
                title: "Are you sure?",
                text: "You wanna return this bag in Event?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                allowOutsideClick: false,
                confirmButtonText: "Yes, return it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const name = localStorage.getItem("name");
                    this.logs(
                        name +
                            " returned the " +
                            bagName +
                            " at the " +
                            this.eventName.replace(/_/g, " ") +
                            " Inventory"
                    );
                    axios
                        .post("/return_bag", {
                            id: id,
                            status: "Returned",
                            spot: this.eventName.replace(/_/g, " "),
                            not: "returnBackEventInventory",
                            sellerName: sellerName,
                        })
                        .then((res) => {
                            this.mount();
                            this.$swal({
                                icon: "success",
                                title: "Returned!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        });
                }
            });
        },
        showTickets(id, sellerName, bagName) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_bags/inside_bag/all_tickets",
                query: {
                    event_id: [this.eventId, String(id), sellerName, bagName],
                },
            });
        },
        mount() {
            const countingPage = this.currentPage * this.currentPerPage;

            this.date =
                this.$route.query.searchDate === undefined
                    ? "undefined"
                    : moment(new Date(this.$route.query.searchDate)).format(
                          "MM-DD-YY"
                      );
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4];
            this.eventName = this.$route.path.split("/")[5];
            this.eventId = this.$route.query.event_id;

            axios
                .get(
                    "/api/get_event_inventory_bags/" +
                        [
                            countingPage,
                            this.date,
                            this.unitId,
                            this.eventId,
                            "All",
                        ]
                )
                .then((res) => {
                    let urls = [
                        "/api/get_event_inventory_bags/" +
                            [
                                countingPage,
                                this.date,
                                this.unitId,
                                this.eventId,
                                "All",
                            ],
                        "/api/get_event_inventory_bags/" +
                            [
                                countingPage,
                                this.date,
                                this.unitId,
                                this.eventId,
                                "Active",
                            ],
                        "/api/get_event_inventory_bags/" +
                            [
                                countingPage,
                                this.date,
                                this.unitId,
                                this.eventId,
                                "Not Active",
                            ],
                    ];
                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });

                    this.getData = res.data.status.data;
                    this.getData2 = res.data.status.data;

                    this.bags = res.data.status.data.map((zzz) => zzz.bag_name);
                    this.load = false;
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
    },
};
</script>
