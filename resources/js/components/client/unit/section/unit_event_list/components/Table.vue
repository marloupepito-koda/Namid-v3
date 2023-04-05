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
                        <th class="text-left">Event Name</th>
                        <th class="text-left">Event Location</th>
                        <th class="text-left">Start At</th>
                        <th class="text-left">End At</th>
                        <th class="text-center p-0">Edit</th>
                        <th class="text-center p-0">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            <v-btn
                                variant="text"
                                @click="
                                    nextPage(
                                        i.unitid,
                                        i.ee_events_unit_name,
                                        i.events_name,
                                        i.id
                                    )
                                "
                                ><u>{{ i.events_name }}</u></v-btn
                            >
                        </td>
                        <td>{{ i.events_address }}</td>
                        <td>{{ i.start }}</td>
                        <td>{{ i.end }}</td>
                        <td class="p-0">
                            <ModalEdit :datas="i" />
                        </td>
                        <td class="p-0">
                            <v-btn
                                size="small"
                                class="ma-2"
                                variant="outlined"
                                color="danger"
                                @click="deleteEvent(i.id)"
                            >
                                <v-icon size="large">
                                    mdi-trash-can-outline
                                </v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
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
    methods: {
        deleteEvent(id) {
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
                        .delete("/api/delete_event/" + id)
                        .then((err) => {
                            this.$swal({
                                icon: "success",
                                title: "Event has been deleted.",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.mount();
                        })
                        .catch((err) => {});
                }
            });
        },
        nextPage(unitId, unitName, eventName, eventId) {
            this.$router.push({
                path:
                    "/client/branch/" +
                    unitId +
                    "/" +
                    eventId +
                    "/event_inventory",
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
        async mount() {
            this.unitId = this.$route.path.split("/")[3];
            axios.get("/api/get_events_in_unit/" + this.unitId).then((res) => {
                this.getData = res.data.status;
                this.getData2 = res.data.status;
                this.load = false;
            });
        },
    },
    data() {
        return {
            load: true,
            unitId: "",
            search: [],
            getData: [],
            getData2: [],
        };
    },
};
</script>
