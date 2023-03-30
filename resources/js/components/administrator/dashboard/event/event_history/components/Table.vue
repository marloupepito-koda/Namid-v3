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
                        <th class="text-left">Name of Bags</th>
                        <th class="text-left">Sellers</th>
                        <th class="text-left">Ticket Type</th>
                        <th class="text-left">Starting #</th>
                        <th class="text-left">Ending #</th>
                        <th class="text-left">Total</th>
                        <th class="text-left">Price</th>
                        <th class="text-left">Amount</th>
                        <th class="text-left">Sold At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>{{ i.ticket_bag }}</td>
                        <td>{{ i.seller_name }}</td>
                        <td>{{ i.ticket_name }}</td>
                        <td>{{ i.ticket_start_number }}</td>
                        <td>{{ i.ticket_end_number }}</td>
                        <td>{{ i.ticket_price }}</td>
                        <td>
                            {{
                                i.ticket_end_number - i.ticket_start_number + 1
                            }}
                        </td>
                        <td>{{ i.ticket_total }}</td>
                        <td>
                            <div v-if="i.ticket_date2 === null">
                                Remaining Tickets
                            </div>
                            <div v-else>
                                {{ i.ticket_date2 }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-card>
    </div>
</template>

<script>
import moment from "moment";

export default {
    components: {},
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
    },
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.unitName = this.$route.path.split("/")[4];
        this.eventName = this.$route.path.split("/")[5];
        this.eventId = this.$route.query.event_id;

        this.date =
            this.$route.query.searchDate === undefined
                ? moment(new Date()).format("LLL")
                : moment(new Date(this.$route.query.searchDate)).format("LLL");

        axios
            .get("/api/get_event_ticket_history/" + [this.unitId, this.eventId])

            .then((res) => {
                let urls = [
                    "/api/get_event_ticket_history/" +
                        [this.unitId, this.eventId],
                ];
                caches.open("static_cache").then((cache) => {
                    cache.addAll(urls).then(() => {
                        console.log("Data cached ");
                    });
                });

                this.getData = Object.values(res.data.status);
                this.getData2 = Object.values(res.data.status);
                this.load = false;
            });
    },
};
</script>
