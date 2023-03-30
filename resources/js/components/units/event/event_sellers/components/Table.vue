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
                        <th class="text-left">Name of Seller</th>
                        <th class="text-left">Designated Bag</th>
                        <th class="text-left">Seller Location</th>
                        <!-- <th class="text-left">Designated At</th> -->
                        <th class="text-center">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            <a
                                href="javascript:;"
                                @click="showTickets(i.id, i.seller_name)"
                                >{{ i.seller_name }}</a
                            >
                        </td>
                        <td>
                            <a
                                href="javascript:;"
                                @click="showTickets(i.id, i.seller_name)"
                                >{{ i.bag_name }}</a
                            >
                        </td>
                        <td>{{ i.location }}</td>
                        <!-- <td>{{ i.ticket_date }}</td> -->
                        <td>
                            <ModalEdit
                                :editId="i.id"
                                :editSellerName="i.seller_name"
                                :editBagId="i.id"
                                :editLocation="i.location"
                                :editBagName="i.bag_name"
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
import ModalEdit from "./ModalEdit.vue";

export default {
    components: {
        ModalEdit,
    },
    data: () => ({
        load: true,
        getData: [],
        getData2: [],
        eventId: "",
        unitId: "",
        unitName: "",
        eventName: "",
        date: moment(new Date()).format("M-D-YY"),
    }),
    methods: {
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
        showTickets(id, sellerName) {
            this.$router.push({
                path:
                    "/unit/branch/" +
                    this.unitId +
                    "/" +
                    this.unitName +
                    "/" +
                    this.eventName +
                    "/event_bags/inside_bag/all_tickets",
                query: { event_id: [this.eventId, String(id), sellerName] },
            });
        },
    },
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.unitName = this.$route.path.split("/")[4];
        this.eventName = this.$route.path.split("/")[5];
        this.eventId = this.$route.query.event_id[0];

        this.date =
            this.$route.query.searchDate === undefined
                ? moment(new Date()).format("LLL")
                : moment(new Date(this.$route.query.searchDate)).format("LLL");
        axios
            .post("/get_every_seller_spot2", {
                date: this.date,
                foreign_unit: this.unitId,
                foreign_spot: this.eventId,
            })
            .then((res) => {
                this.getData = Object.values(res.data.status);
                this.getData2 = Object.values(res.data.status);
                this.load = false;
            });
    },
};
</script>
