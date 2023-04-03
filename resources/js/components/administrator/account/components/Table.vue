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
                        <th class="text-left">Full Name</th>
                        <th class="text-left">Assigned Unit</th>
                        <th class="text-left">Username</th>
                        <th class="text-left">Job Position</th>
                        <th class="text-center p-0">Access</th>
                        <th class="text-center p-0">Edit</th>
                        <th class="text-center p-0">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in getData" :key="i.name">
                        <td>
                            {{ i.name }}
                        </td>
                        <td>
                            {{
                                i.unitname === "" || i.unitname === null
                                    ? "NAMID"
                                    : i.unitname
                            }}
                        </td>
                        <td>{{ i.email }}</td>
                        <td>
                            {{
                                i.usertype === "Administrator"
                                    ? "Admin"
                                    : i.usertype
                            }}
                        </td>
                        <td><ModalAccess :editId="i.id" /></td>
                        <td class="p-0">
                            <ModalEdit :editId="i.id" />
                        </td>
                        <td class="p-0">
                            <v-btn
                                size="small"
                                class="ma-2"
                                variant="outlined"
                                color="danger"
                                @click="deleteAccount(i.id)"
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
import ModalAccess from "./ModalAccess.vue";
export default {
    components: {
        ModalEdit,
        ModalAccess,
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
        deleteAccount(id) {
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
                    axios.delete("/delete_user/" + id).then((res) => {
                        this.$swal({
                            icon: "success",
                            title: "User has been deleted.",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.mount();
                    });
                }
            });
        },
        nextPage(unitId, unitName, eventName, eventId) {
            this.$router.push({
                path:
                    "/administrator/dashboard/" +
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
        mount() {
            axios
                .get("/api/get_all_users")
                .then((res) => {
                    this.getData = res.data.status;
                    this.getData2 = res.data.status;
                    this.load = false;
                })
                .catch((err) => {});
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
