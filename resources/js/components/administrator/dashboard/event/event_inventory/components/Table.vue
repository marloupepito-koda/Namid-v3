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
                        <th class="text-left">Ticket Type</th>
                        <th class="text-left">Ticket Name</th>
                        <th class="text-left">Starting #</th>
                        <th class="text-left">Ending #</th>
                        <th class="text-left">Bind</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Count</th>
                        <th class="text-left">ATP</th>
                        <th class="text-left">Remaining</th>
                        <th class="text-left">Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            <v-btn
                                size="small"
                                variant="flat"
                                @click="showBags(i.id, i.start, i.end)"
                            >
                                <u class="text-blue"> {{ i.ticket_type }}</u>
                            </v-btn>
                        </td>
                        <td>{{ i.ticket_name }}</td>
                        <td>{{ i.start }}</td>
                        <td>{{ i.end }}</td>

                        <td>{{ i.bind }}</td>
                        <td>{{ i.price }}</td>
                        <td>{{ i.count }}</td>
                        <td>{{ i.price / i.count }}</td>

                        <td>{{ i.remaining }}</td>
                        <td>{{ i.date }}</td>
                        <td>
                            <v-chip
                                size="small"
                                v-if="i.status === null"
                                color="red"
                                text-color="white"
                                >Unused</v-chip
                            >
                            <v-chip
                                size="small"
                                v-else
                                color="success"
                                text-color="white"
                                >{{ i.status }}</v-chip
                            >
                        </td>
                        <td>
                            <ModalEdit :editId="i" />
                        </td>
                        <!-- <td>
                            <v-btn
                                size="small"
                                class="ma-2"
                                variant="outlined"
                                color="danger"
                                @click="deleteTickets(i.id)"
                            >
                                <v-icon size="large" color="danger">
                                    mdi-trash-can-outline
                                </v-icon>
                            </v-btn>
                        </td> -->
                    </tr>
                </tbody>
            </v-table>
        </v-card>
    </div>
</template>

<script>
import ModalEdit from "./ModalEdit.vue";
export default {
    components: {
        ModalEdit,
    },
    mounted() {
        this.mount();
    },
    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.mount();
            }
        );
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
        showBags(id, start, end) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.eventId.replace(/ /g, "_") +
                    "/show_bags",
                query: {
                    event_id: String(this.eventId),
                    subid: String(id),
                    start: String(start),
                    end: String(end),
                },
            });
        },
        deleteTickets(id) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post("/delete_ticket_in_events", {
                            id: id,
                            eventid: this.eventId,
                        })
                        .then((res) => {
                            this.mount();
                            this.$swal({
                                icon: "success",
                                title: "Success!",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                        });
                }
            });
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];
            axios
                .get(
                    "/api/get_ticket_inventory/" +
                        this.unitId +
                        "/" +
                        this.eventId
                )
                .then((res) => {
                    this.getData = Object.values(res.data.status);
                    this.getData2 = Object.values(res.data.status);
                    this.load = false;
                    const search =
                        this.$route.query.ticketid === undefined
                            ? ""
                            : this.$route.query.ticketid;
                    this.searchData(search);
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
