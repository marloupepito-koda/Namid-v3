<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="blue"
            @click="showTicketEvent(ticketId)"
        >
            <v-icon size="large"> mdi-clipboard-text-clock-outline</v-icon>
        </v-btn>

        <v-dialog v-model="dialog" width="1000">
            <v-card>
                <div class="container">
                    <table class="table table-striped mt-5 mb-5">
                        <thead>
                            <tr>
                                <th scope="col">Event Name</th>
                                <th scope="col">Ticket Type</th>
                                <th scope="col">Starting #</th>
                                <th scope="col">Ending #</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(i, index) in showData">
                                <td>{{ i.ee_events_unit_spot }}</td>
                                <td>{{ i.ticket_type }}</td>
                                <td>{{ i.starting }}</td>
                                <td>{{ parseInt(i.ending) }}</td>
                                <td>
                                    <v-btn
                                        size="x-small"
                                        class="ma-2"
                                        variant="outlined"
                                        color="danger"
                                        @click="deleteSubInventory(i.id)"
                                    >
                                        <v-icon> mdi-trash-can-outline</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props: ["ticketId"],
    data() {
        return {
            dialog: false,
            showData: [],
            unitId: "",
            unitName: "",
        };
    },
    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
        },
        deleteSubInventory(id) {
            this.dialog = false;
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
                        .post("/delete_sub_inventory", {
                            id: id,
                        })
                        .then((res) => {
                            this.$swal({
                                icon: "success",
                                title: "Your work has been saved",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.$router.push({
                                path:
                                    "/administrator/dashboard/" +
                                    this.unitId +
                                    "/" +
                                    this.unitName.replace(/ /g, "_") +
                                    "/loading",
                                query: { where: "unit_ticket_inventory" },
                            });
                        });
                }
            });
        },
        showTicketEvent(id) {
            axios
                .post("/get_event_ticket_assigned", {
                    id: id,
                })
                .then((res) => {
                    this.dialog = true;
                    this.showData = res.data.status;
                });
        },
    },
};
</script>
