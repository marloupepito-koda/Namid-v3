<template>
    <div class="row">
        <div class="col-md-3 offset-md-9"><Modal /></div>
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
                                <th class="text-center p-0">
                                    Ticket Transactions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="i in getData" :key="i.name">
                                <td>{{ i.ticket_type }}</td>
                                <td>{{ i.ticket_name }}</td>
                                <td>{{ i.start }}</td>
                                <td>{{ i.end }}</td>
                                <td>{{ i.quantity }}</td>
                                <td>{{ i.count }}</td>
                                <td>{{ i.bind }}</td>
                                <td>{{ i.date }}</td>
                                <td>
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
    methods: {
        showTicketEvent(id) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
                    this.unitId +
                    "/" +
                    this.eventName.replace(/ /g, "_") +
                    "/unit_ticket_inventory/events",
                query: {
                    ticketid: String(id),
                },
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
                .get("/api/get_unit_ticket_inventory/" + this.unitId)
                .then((res) => {
                    this.getData = res.data.status;
                    this.getData2 = res.data.status;
                    this.load = false;
                    console.log(res.data.status);
                });
        },
    },
    data() {
        return {
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
