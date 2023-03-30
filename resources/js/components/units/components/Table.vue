<template>
    <div>
        <center v-if="load === true" style="margin-top: 150px">
            <v-progress-circular
                indeterminate
                color="black"
            ></v-progress-circular>
        </center>
        <v-table density="comfortable" v-else>
            <thead>
                <tr>
                    <th class="text-left">Full Company Name</th>
                    <th class="text-left">Company Short Name</th>
                    <th class="text-left">Company Code</th>
                    <th class="text-left">Created At</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item, index) in units" :key="index">
                    <td>
                        <router-link
                            :to="
                                '/administrator/dashboard/' +
                                item.client_id +
                                '/' +
                                item.ee_client_unit_name.replace(/ /g, '_') +
                                '/event_list'
                            "
                            >{{ item.ee_client_unit_fullname }}</router-link
                        >
                    </td>
                    <td>{{ item.ee_client_unit_name }}</td>
                    <td>{{ item.ee_client_company_code }}</td>
                    <td>
                        {{
                            new Date(item.created_at).getMonth() +
                            1 +
                            "/" +
                            new Date(item.created_at).getDate() +
                            "/" +
                            new Date(item.created_at).getFullYear()
                        }}
                    </td>
                    <td>
                        <ModalEdit
                            :editId="[
                                item.client_id,
                                item.ee_client_unit_fullname,
                                item.ee_client_unit_name,
                                item.ee_client_company_code,
                            ]"
                        />
                    </td>
                    <td>
                        <v-btn
                            size="small"
                            class="ma-2"
                            variant="outlined"
                            color="danger"
                            @click="deleteUnit(item.client_id)"
                        >
                            <v-icon size="large" color="danger">
                                mdi-trash-can-outline
                            </v-icon>
                        </v-btn>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>

<script>
import axios from "axios";
import ModalEdit from "./ModalEdit.vue";
export default {
    data() {
        return {
            units: [],
            load: true,
        };
    },
    components: {
        ModalEdit,
    },
    mounted() {
        this.mount();
    },
    methods: {
        deleteUnit(id) {
            this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this! All the tickets and spots will be deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: true,
                allowOutsideClick: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post("/delete_unit", {
                            unitid: id,
                        })
                        .then((response) => {
                            this.units = response.data.status;

                            this.$swal({
                                icon: "success",
                                title: "the Unit has been deleted successfully",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            this.$router.push({
                                path: "/administrator/loading",
                            });
                        })
                        .catch((error) => {
                            this.$swal(`Request failed: ${error}`);
                        });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === this.$swal.DismissReason.cancel
                ) {
                    this.$swal({
                        icon: "error",
                        title: "Deletion has been cancelled!",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });
        },
        mount() {
            axios
                .get("/api/get_all_units")
                .then((res) => {
                    this.units = res.data.status;
                    this.load = false;
                    let urls = ["/api/get_all_units"];

                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                })
                .catch((err) => {});
        },
    },
};
</script>
