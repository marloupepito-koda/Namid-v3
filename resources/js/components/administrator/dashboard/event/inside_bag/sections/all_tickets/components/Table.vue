<template>
    <div>
        <center v-if="load === true" style="margin-top: 150px">
            <v-progress-circular
                indeterminate
                color="black"
            ></v-progress-circular>
        </center>
        <v-card class="mt-3" v-else>
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
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-left">Ticket Type</th>
                        <th class="text-left">Bag Name</th>
                        <!-- <th class="text-left">Seller Name</th> -->
                        <!-- <th class="text-left">Bind</th> -->
                        <th class="text-left">Count</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">ATP</th>
                        <th class="text-left">Quantity</th>
                        <th class="text-left">Amount</th>
                        <th class="text-left">Starting #</th>
                        <th class="text-left">Ending #</th>
                        <th class="text-left">Sold At</th>
                        <th class="text-left">Designated At</th>
                        <th class="text-left">Info</th>
                        <th class="text-center">TRANSFER</th>
                        <th class="text-center">EDIT</th>
                        <th class="text-center">SOLD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            {{ i.ticket_name }}
                        </td>
                        <td>{{ i.ticket_bag }}</td>
                        <!-- <td>{{ i.seller_name }}</td>
                        <td>{{ i.bind_name }}</td> -->
                        <td>{{ i.ticket_count }}</td>
                        <td>
                            {{
                                i.ticket_price.replace(
                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                    "$1,"
                                )
                            }}
                        </td>
                        <td>{{ i.atp }}</td>
                        <td>{{ i.ticket_remaining }}</td>
                        <td v-if="i.ticket_total !== null">
                            {{
                                i.ticket_total.replace(
                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                    "$1,"
                                )
                            }}
                        </td>
                        <td v-else>0</td>
                        <td>
                            {{
                                i.ticket_start_number.replace(
                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                    "$1,"
                                )
                            }}
                        </td>
                        <td>
                            {{
                                i.ticket_end_number.replace(
                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                    "$1,"
                                )
                            }}
                        </td>
                        <td>
                            {{
                                i.ticket_date2 === null
                                    ? "Unsold"
                                    : new Date(i.ticket_date2).getMonth() +
                                      1 +
                                      "/" +
                                      new Date(i.ticket_date2).getDate() +
                                      "/" +
                                      new Date(i.ticket_date2).getFullYear()
                            }}
                        </td>
                        <td class="text-center">
                            {{ i.ticket_date }}
                        </td>
                        <td>
                            <v-tooltip :text="i.remember_token">
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        color="black"
                                        size="x-small"
                                        v-bind="props"
                                        icon="mdi-information-variant"
                                    ></v-btn>
                                </template>
                            </v-tooltip>
                        </td>
                        <td
                            class="text-center"
                            v-if="
                                new Date().getDate() !==
                                new Date(i.created_at).getDate()
                            "
                        >
                            <Option
                                :unitList="unitList"
                                :soldDate="
                                    i.ticket_date2 === null ? false : true
                                "
                                :editId="[
                                    i.id,
                                    i.ticket_start,
                                    i.ticket_end_number,
                                    i.ticket_name,
                                    i.ticket_bag,
                                ]"
                            />
                        </td>
                        <td v-else>Unavailable</td>
                        <td>
                            <ModalEdit
                                :soldDate="
                                    i.ticket_date2 === null ? false : true
                                "
                                :editId="[
                                    i.id,
                                    i.ticket_name,
                                    i.ticket_start_number,
                                    i.ticket_end_number,
                                    i.ticket_price,
                                    parseInt(i.ticket_count),
                                ]"
                            />
                        </td>

                        <td>
                            <ModalSold
                                :soldDate="
                                    i.ticket_date2 === null ? false : true
                                "
                                :editId="[
                                    i.id,
                                    i.ticket_name,
                                    i.ticket_bag,
                                    i.ticket_start_number,
                                    i.ticket_end_number,
                                    i.ticket_price,
                                    i.ee_ticket_id,
                                    i.ticket_remaining,
                                    i.ticket_total,
                                    i.ticket_start,
                                    i.global_ticket_id,
                                    i.bind_name,
                                    i.atp,
                                    i.ticket_count,
                                ]"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </v-card>
    </div>
</template>

<script>
import moment from "moment";
import ModalEdit from "./../../components/ModalEdit.vue";
import ModalSold from "./../../components/ModalSold.vue";
import Option from "./../../components/Option.vue";
export default {
    props: ["unitList"],
    components: {
        ModalEdit,
        ModalSold,
        Option,
    },
    mounted() {
        this.mount();
    },
    data: () => ({
        load: true,
        items: [
            { title: "Mark Sold", id: "Sold" },
            { title: "Edit Price", id: "price" },
        ],
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
        bagId: "",
    }),
    methods: {
        showTickets(id) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_bags/inside_bag/all_tickets",
                query: { event_id: [this.eventId, String(id)] },
            });
        },
        mount() {
            const countingPage = this.currentPage * this.currentPerPage;

            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4];
            this.eventName = this.$route.path.split("/")[5];
            this.eventId = this.$route.query.event_id[0];
            this.bagId = this.$route.query.event_id[1];

            this.date =
                this.$route.query.searchDate === undefined
                    ? "undefined"
                    : moment(new Date(this.$route.query.searchDate)).format(
                          "MM-DD-YYYY"
                      );
            // console.log("wwww", this.date);
            axios
                .get(
                    "/api/get_event_bags/" +
                        [this.date, this.unitId, this.eventId, this.bagId]
                )
                .then((res) => {
                    this.getData = res.data.status;
                    this.getData2 = res.data.status;
                    this.load = false;

                    let urls = [
                        "/api/get_event_bags/" +
                            [this.date, this.unitId, this.eventId, this.bagId],
                    ];
                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });

                    if (this.eventName === "unit_ticket_inventory") {
                        this.search = this.$route.query.event_id[3];
                        this.searchData("insertText");
                    }
                });
        },
        searchData(e) {
            const keyword = this.search;
            if (e.inputType === "insertText" || e === "insertText") {
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
