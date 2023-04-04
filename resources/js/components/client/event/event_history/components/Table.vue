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
                        <td>{{ i.bag_name }}</td>
                        <td>{{ i.seller }}</td>
                        <td>{{ i.ticket_type }}</td>
                        <td>{{ i.start }}</td>
                        <td>{{ i.end }}</td>
                        <td>{{ i.price }}</td>
                        <td>
                            {{ i.end - i.start + 1 }}
                        </td>
                        <td>{{ i.end - i.start + 1 * i.price }}</td>
                        <td>
                            <div>
                                {{ i.date }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
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
        this.eventId = this.$route.path.split("/")[4];
        this.date =
            this.$route.query.searchDate === undefined
                ? moment(new Date()).format("LLL")
                : moment(new Date(this.$route.query.searchDate)).format("LLL");

        axios
            .get(
                "/api/get_event_ticket_sold_history/" +
                    this.unitId +
                    "/" +
                    this.eventId
            )

            .then((res) => {
                console.log("waaaaa", res.data.status);
                this.getData = Object.values(res.data.status);
                this.getData2 = Object.values(res.data.status);
                this.load = false;
            });
    },
};
</script>
