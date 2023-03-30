<template>
    <div>
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            color="green"
            @click="openModal(editId)"
        >
            <v-icon size="large"> mdi-account-key-outline </v-icon>
        </v-btn>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">ACCOUNT ACCESS</span>
                    </v-card-title>

                    <div class="table-responsive form-inline">
                        <table class="table-hover table">
                            <thead>
                                <tr>
                                    <th>
                                        <h4 class="m-0">
                                            {{ data.name }}
                                        </h4>
                                    </th>
                                    <th>
                                        <small> {{ data.usertype }}</small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="highlight">
                                    <td colspan="2">
                                        <b>ABOUT THE COMPANY</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Unit Name</td>
                                    <td>
                                        {{ unit.ee_client_unit_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Company Name</td>
                                    <td>
                                        {{ unit.ee_client_unit_shortname }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Full Company Name</td>
                                    <td>
                                        <small>{{
                                            unit.ee_client_unit_fullname
                                        }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field">Company Code</td>
                                    <td>
                                        {{ unit.ee_client_company_code }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive form-inline">
                        <table class="table-hover table">
                            <tbody>
                                <tr class="highlight">
                                    <td colspan="2">
                                        <b>SYSTEM ACCESS</b>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="field" style="width: 90%">
                                        1. Allow to login
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access1,
                                                        1,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access1 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access1 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        2. Setup new event
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                @click="
                                                    clickMe(
                                                        data.access2,
                                                        2,
                                                        data.id
                                                    )
                                                "
                                                type="checkbox"
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access2 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access2 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        3. Setup ticket in a unit
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access3,
                                                        3,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access3 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access3 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        4. Allow to return the bag in the event
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access4,
                                                        4,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access4 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access4 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        5. Setup ticket in an event
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access5,
                                                        5,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access5 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access5 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        6. Setup bag in an event
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access6,
                                                        6,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access6 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access6 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        7. Allow to return of bag into an event.
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access7,
                                                        7,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access7 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access7 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        8. Allow to add tickets in a bag.
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access8,
                                                        8,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access8 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access8 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        9. Allow to add seller and assign a bag.
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access9,
                                                        9,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access9 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access9 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        10. Allow to mark the ticket sold
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access10,
                                                        10,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access10 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access10 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        11. Allow to edit ticket pricing.
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access11,
                                                        11,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access11 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access11 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td class="field" style="width: 90%">
                                        12. Allow to edit a seller.
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access12,
                                                        12,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access12 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access12 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field" style="width: 90%">
                                        13. Allow to show the ticket history
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="
                                                    clickMe(
                                                        data.access13,
                                                        13,
                                                        data.id
                                                    )
                                                "
                                                id="flexSwitchCheckDefault"
                                                :checked="
                                                    data.access13 === 'On'
                                                        ? true
                                                        : false
                                                "
                                            />
                                            <label
                                                class="form-check-label"
                                                for="flexSwitchCheckDefault"
                                                >{{
                                                    data.access13 === "On"
                                                        ? "On"
                                                        : "Off"
                                                }}</label
                                            >
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                        <br />
                        <div class="col-md-12">
                            <input
                                type="submit"
                                @click="saveUser"
                                value="SAVE"
                                class="btn btn-xs d-block bg-dark text-white w-100"
                            />
                        </div>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import moment from "moment";
export default {
    props: ["editId"],
    components: { Datepicker },
    data: () => ({
        dialog: false,
        valid: false,
        access1: "",
        access2: "",
        access3: "",
        access4: "",
        access5: "",
        access6: "",
        access7: "",
        access8: "",
        access9: "",
        access10: "",
        access11: "",
        access12: "",
        access13: "",
        access14: "",
        access15: "",
        name: "",
        units: [],
        email: "",
        job: "",
        unitName: "",
        password: "",
        id: "",
        data: [],
        unit: [],
    }),
    mounted() {},
    methods: {
        clickMe(e, number, id) {
            const destination = "access" + number;
            const value = e === "On" ? "Off" : "On";

            if (destination === "access1") {
                localStorage.setItem(destination, value);
                this.data.access1 = value;
            } else if (destination === "access2") {
                localStorage.setItem(destination, value);
                this.data.access2 = value;
            } else if (destination === "access3") {
                localStorage.setItem(destination, value);
                this.data.access3 = value;
            } else if (destination === "access4") {
                localStorage.setItem(destination, value);
                this.data.access4 = value;
            } else if (destination === "access5") {
                localStorage.setItem(destination, value);
                this.data.access5 = value;
            } else if (destination === "access6") {
                localStorage.setItem(destination, value);
                this.data.access6 = value;
            } else if (destination === "access7") {
                localStorage.setItem(destination, value);
                this.data.access7 = value;
            } else if (destination === "access8") {
                localStorage.setItem(destination, value);
                this.data.access8 = value;
            } else if (destination === "access9") {
                localStorage.setItem(destination, value);
                this.data.access9 = value;
            } else if (destination === "access10") {
                localStorage.setItem(destination, value);
                this.data.access10 = value;
            } else if (destination === "access11") {
                localStorage.setItem(destination, value);
                this.data.access11 = value;
            } else if (destination === "access12") {
                localStorage.setItem(destination, value);
                this.data.access12 = value;
            } else if (destination === "access13") {
                localStorage.setItem(destination, value);
                this.data.access13 = value;
            } else if (destination === "access14") {
                localStorage.setItem(destination, value);
                this.data.access14 = value;
            } else if (destination === "access15") {
                localStorage.setItem(destination, value);
                this.data.access15 = value;
            }
        },
        saveUser() {
            axios
                .post("/edit_access_user", {
                    access1: this.data.access1,
                    access2: this.data.access2,
                    access3: this.data.access3,
                    access4: this.data.access4,
                    access5: this.data.access5,
                    access6: this.data.access6,
                    access7: this.data.access7,
                    access8: this.data.access8,
                    access9: this.data.access9,
                    access10: this.data.access10,
                    access11: this.data.access11,
                    access12: this.data.access12,
                    access13: this.data.access13,
                    access14: this.data.access14,
                    access15: this.data.access15,
                    id: this.editId,
                })

                .then((res) => {
                    this.data = res.data.status;
                    this.dialog = false;
                    this.$swal({
                        icon: "success",
                        title: "Saved",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                })
                .catch((err) => {});
        },
        openModal() {
            this.dialog = true;
            axios
                .get("/api/get_every_users/" + this.editId)
                .then((res) => {
                    this.unit = res.data.unit;
                    this.data = res.data.status;
                    this.id = res.data.status.id;
                })
                .then((res) => {
                    let urls = ["/api/get_every_users/" + this.editId];

                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                });
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
    },
};
</script>
