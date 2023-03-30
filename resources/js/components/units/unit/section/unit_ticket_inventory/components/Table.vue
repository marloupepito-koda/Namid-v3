<template>
    <div class="row">
        <div class="col-md-3 offset-md-9" v-if="access3 === 'On'">
            <Modal />
        </div>
        <div class="col-md-12">
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
                                <th class="text-left">Quantity</th>
                                <th class="text-left">Count</th>
                                <th class="text-left">Bind</th>
                                <th class="text-left">Created At</th>
                                <!-- <th class="text-center p-0">
                                    Add Ticket To Events
                                </th> -->
                                <th class="text-center p-0">
                                    Ticket Transactions
                                </th>
                                <!-- <th class="text-center p-0">Delete</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="i in getData" :key="i.name">
                                <td>{{ i.ticket_number }}</td>
                                <td>{{ i.ticket_name }}</td>
                                <td>{{ i.ticket_start }}</td>
                                <td>{{ i.ticket_end }}</td>
                                <td>{{ i.status }}</td>
                                <td>{{ i.ticket_count }}</td>
                                <td>{{ i.bind_name }}</td>
                                <td>{{ i.ticket_date }}</td>
                                <!-- <td class="p-0">
                                    <ModalAdd :ticketId="i.ee_ticket_id" />
                                </td> -->
                                <td>
                                    <!-- <ModalShow :ticketId="i.ee_ticket_id" /> -->
                                    <v-btn
                                        size="small"
                                        class="ma-2"
                                        variant="outlined"
                                        color="blue"
                                        @click="showTicketEvent(i.ee_ticket_id)"
                                    >
                                        <v-icon size="large">
                                            mdi-clipboard-text-clock-outline</v-icon
                                        >
                                    </v-btn>
                                </td>
                                <!-- <td class="p-0">
                            <v-btn
                                size="small"
                                class="ma-2"
                                variant="outlined"
                                color="danger"
                                @click="deleteTicket(i.ee_ticket_id)"
                            >
                                <v-icon size="large">
                                    mdi-trash-can-outline</v-icon
                                >
                            </v-btn>
                        </td> -->
                            </tr>
                        </tbody>
                    </v-table>
                </v-card>
            </div>
        </div>
    </div>
</template>

<script>
import ModalShow from "./ModalShow.vue";
import Modal from "./Modal.vue";
export default {
    components: {
        ModalShow,
        Modal,
    },
    mounted() {
        this.access3 = JSON.parse(localStorage.getItem("access")).access3;
        this.mount();
    },
    methods: {
        showTicketEvent(id) {
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/unit_ticket_inventory/events",
                query: {
                    ticketid: String(id),
                },
            });
        },
        deleteTicket(id) {
            console.log(id);
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
                        .post("/delete_global_ticket", {
                            id: id,
                        })
                        .then((res) => {
                            this.$swal({
                                icon: "success",
                                title: "Your work has been deleted",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.mount();
                        })
                        .catch((err) => {
                            this.$swal({
                                icon: "error",
                                title: "Error!",
                                showConfirmButton: false,
                                timer: 1500,
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
            this.eventName = this.$route.path.split("/")[4].replace(/_/g, " ");
            axios
                .get(
                    "/api/ee_get_all_global_ticket/" +
                        this.unitId +
                        "," +
                        this.eventName.replace(/ /g, "_")
                )
                .then((res) => {
                    this.getData = res.data.status;
                    this.getData2 = res.data.status;
                    this.load = false;

                    let urls = [
                        "/api/ee_get_all_global_ticket/" +
                            this.unitId +
                            "," +
                            this.eventName.replace(/ /g, "_"),
                        "/api/ee_get_all_global_ticket2/" +
                            this.unitId +
                            "," +
                            this.eventName.replace(/ /g, "_"),
                    ];
                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                });
        },
    },
    data() {
        return {
            access3: "",
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
