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
                            <router-link
                                to=""
                                @click="
                                    nextPage(
                                        i.foreign_id,
                                        i.ee_events_unit_name,
                                        i.ee_events_unit_spot,
                                        i.event_id
                                    )
                                "
                                >{{ i.ee_events_unit_spot }}</router-link
                            >
                        </td>
                        <td>{{ i.ee_events_unit_location }}</td>
                        <td>{{ i.ee_events_unit_start_date }}</td>
                        <td>{{ i.ee_events_unit_end_date }}</td>
                        <td class="p-0">
                            <ModalEdit :editId="i.event_id" />
                        </td>
                        <td class="p-0">
                            <v-btn
                                size="small"
                                class="ma-2"
                                variant="outlined"
                                color="danger"
                                @click="deleteEvent(i.event_id)"
                            >
                                <v-icon size="large">
                                    mdi-trash-can-outline
                                </v-icon>
                            </v-btn>
                        </td>
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
                        .post("/event_delete", {
                            id: id,
                        })
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
                    "/unit/branch/" +
                    unitId +
                    "/" +
                    unitName.replace(/ /g, "_") +
                    "/" +
                    eventName.replace(/ /g, "_") +
                    "/event_inventory?",
                query: { event_id: [eventId] },
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
            this.eventName = this.$route.path.split("/")[4].replace(/_/g, " ");
            axios.get("/api/get_all_spots/" + this.unitId).then((res) => {
                this.getData = res.data.status;
                this.getData2 = res.data.status;
                this.load = false;
                let urls = ["/api/get_all_spots/" + this.unitId];
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
