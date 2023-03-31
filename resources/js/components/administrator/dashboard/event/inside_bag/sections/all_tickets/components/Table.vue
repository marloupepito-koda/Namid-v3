<template>
    <div>
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
                    <th class="text-left">Count</th>
                    <th class="text-left">Price</th>
                    <th class="text-left">ATP</th>
                    <th class="text-left">Quantity</th>
                    <th class="text-left">Amount</th>
                    <th class="text-left">Starting #</th>
                    <th class="text-left">Ending #</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Sold At</th>
                    <th class="text-left">Designated At</th>
                    <th class="text-left">Info</th>
                    <th class="text-center">TRANSFER</th>
                    <th class="text-center">EDIT</th>
                    <th class="text-center">SOLD</th>
                </tr>
                <tr v-for="i in getData" :key="i.name">
                    <td>
                        {{ i.ticket_type }}
                    </td>
                    <td>{{ i.bind }}</td>
                    <td>{{ i.count }}</td>
                    <td>
                        {{ i.price }}
                    </td>
                    <td>{{ parseFloat(i.price) / parseFloat(i.count) }}</td>
                    <td>{{ i.quantity }}</td>
                    <td>
                        {{ i.quantity * i.price }}
                    </td>
                    <td>
                        {{ i.start }}
                    </td>
                    <td>
                        {{ i.end }}
                    </td>
                    <td>
                        <v-chip
                            v-if="i.status === 'Unsold'"
                            color="red"
                            text-color="white"
                        >
                            Unsold
                        </v-chip>
                        <v-chip v-else color="green" text-color="white">
                            Unsold
                        </v-chip>
                    </td>
                    <td>
                        <div v-if="i.status === 'Sold'">
                            {{ i.date }}
                        </div>
                        <div v-else>Not Available</div>
                    </td>
                    <td class="text-center">
                        {{ i.date }}
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
                    <td>
                        <Option :datas="i" />
                    </td>
                    <td>
                        <ModalEdit :datas="i" />
                    </td>

                    <td>
                        <ModalSold :datas="i" />
                    </td>
                </tr>
            </thead>
        </table>
    </div>
</template>

<script>
import moment from "moment";
import ModalEdit from "./../../components/ModalEdit.vue";
import ModalSold from "./../../components/ModalSold.vue";
import Option from "./../../components/Option.vue";
export default {
    components: {
        ModalEdit,
        ModalSold,
        Option,
    },
    data: () => ({
        search: [],
        unitId: "",
        eventId: "",
        bagId: "",
        getData2: [],
        getData: [],
    }),
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
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];
            this.bagId = this.$route.path.split("/")[8];
            axios
                .get(
                    "/api/get_tickets_in_bag/" +
                        this.unitId +
                        "/" +
                        this.eventId +
                        "/" +
                        this.bagId +
                        "/all"
                )
                .then((res) => {
                    this.getData2 = res.data.status;
                    this.getData = res.data.status;
                });
        },
    },
};
</script>
