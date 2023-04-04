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
                            <v-btn
                                variant="text"
                                href="javascript:;"
                                @click="showTickets(i.id, i.seller)"
                                ><u>{{ i.bag_name }}</u></v-btn
                            >
                        </td>
                        <td>
                            <a
                                href="javascript:;"
                                @click="showTickets(i.id, i.seller)"
                                v-if="i.seller !== null"
                                >{{ i.seller }}</a
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
                                i.remaining === null
                                    ? 0
                                    : i.remaining.replace(
                                          /(\d)(?=(\d\d\d)+(?!\d))/g,
                                          "$1,"
                                      )
                            }}
                        </td>

                        <td>{{ i.date }}</td>
                        <td class="text-center">
                            <v-chip
                                size="small"
                                v-if="
                                    i.remaining === null || i.remaining === '0'
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
                                @click="returnBag(i.id, i.seller, i.bag_name)"
                            >
                                <v-icon size="large" color="blue">
                                    mdi-arrow-down-left-bold</v-icon
                                >
                            </v-btn>
                        </td>
                        <td class="text-center">
                            <EditModal :datas="i" />
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-card>
    </div>
</template>

<script>
import moment from "moment";
import EditModal from "./../../../components/EditModal.vue";
export default {
    mounted() {
        this.mount();
    },
    components: {
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
        date: "",
        bags: [],
    }),
    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.mount();
            }
        );
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
        returnBag(id) {
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
                    axios
                        .put("/return_bag", {
                            id: id,
                            status: "Returned",
                            return: "Returned",
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
        showTickets(id) {
            this.$router.push({
                path:
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/inside_bag/all_tickets/" +
                    String(id),
            });
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];

            axios
                .get(
                    "/api/get_event_bags/" +
                        this.unitId +
                        "/" +
                        this.eventId +
                        "/active"
                )
                .then((res) => {
                    this.getData = res.data.status;
                    this.getData2 = res.data.status;
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
