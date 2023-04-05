<template>
    <div>
        <center v-if="load === true" style="margin-top: 150px">
            <v-progress-circular
                indeterminate
                color="black"
            ></v-progress-circular>
        </center>
        <div v-else>
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
                        <th class="text-left">Remaining Ticket in Bag</th>
                        <th class="text-left">Created At</th>
                        <th class="text-left">Returned At</th>
                        <th class="text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            {{ i.bag_name }}
                            <!-- <a
                                href="javascript:;"
                                @click="showTickets(i.id, i.seller, i.bag_name)"
                                ><u>{{ i.bag_name }}</u></a
                            > -->
                        </td>
                        <td>
                            <a
                                href="javascript:;"
                                @click="showTickets(i.id, i.seller, i.bag_name)"
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
                        <td>{{ i.remaining }}</td>
                        <td>{{ i.date }}</td>
                        <!-- <td>{{ i.n_o_s }}</td> -->
                        <td>
                            {{
                                new Date().getMonth(i.updated_at) +
                                1 +
                                "-" +
                                new Date().getDate(i.updated_at) +
                                "-" +
                                new Date().getFullYear(i.updated_at) +
                                " " +
                                new Date().getHours(i.updated_at) +
                                ":" +
                                new Date().getMinutes(i.updated_at) +
                                ":" +
                                new Date().getSeconds(i.updated_at)
                            }}
                        </td>
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
        </div>
    </div>
</template>

<script>
export default {
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
                    "/client/branch/" +
                    this.unitId +
                    "/" +
                    this.eventId +
                    "/event_bags/inside_bag/all_tickets/" +
                    id,
            });
        },
        returnTicket(id) {
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
                        .put("/return_bag", {
                            id: id,
                            status: null,
                            return: "back",
                        })
                        .then((res) => {
                            this.dialog = false;
                            this.$swal({
                                icon: "success",
                                title: "Bag has been returned!",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.$router.push({
                                hash: "#" + Math.floor(Math.random() * 999999),
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
            this.eventId = this.$route.path.split("/")[4];
            axios
                .get(
                    "/api/get_returned_bag/" +
                        this.unitId +
                        "/" +
                        this.eventId +
                        "/Returned"
                )
                .then((res) => {
                    console.log(res.data.status);
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
