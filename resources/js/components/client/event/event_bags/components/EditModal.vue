<template>
    <div class="text-center">
        <v-btn
            size="small"
            class="ma-2"
            variant="outlined"
            large
            color="black"
            @click="openModal(editId)"
        >
            <v-icon size="large" color="black">
                mdi-briefcase-edit-outline</v-icon
            >
        </v-btn>
        <v-dialog v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">Edit Bag and Seller</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="seller"
                            :rules="sellerRules"
                            label="Seller Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="bag_name"
                            :rules="bag_nameRules"
                            label="Bag Name"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <v-text-field
                            v-model="location"
                            :rules="locationRules"
                            label="Location"
                            required
                            variant="outlined"
                            density="compact"
                        ></v-text-field>

                        <div class="col-md-6 mt-5 offset-md-6 mb-3">
                            <v-btn color="error" class="mr-4" @click="reset">
                                CANCEL
                            </v-btn>

                            <v-btn
                                color="black"
                                :loading="loading"
                                class="mr-4"
                                @click="validate"
                            >
                                SAVE
                            </v-btn>
                        </div>
                    </v-form>
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
    props: ["datas"],
    components: { Datepicker },
    data: () => ({
        loading: false,
        dialog: false,
        unitId: "",
        eventId: "",
        valid: true,
        seller: "",
        sellerRules: [(v) => !!v || "Seller Name is required"],
        seller1: "",
        bag_name: "",
        bag_nameRules: [(v) => !!v || "Bag Name is required"],
        location: "",
        locationRules: [(v) => !!v || "Location is required"],
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.mount();
    },
    methods: {
        openModal() {
            this.dialog = true;
            this.seller = this.datas.seller;
            this.bag_name = this.datas.bag_name;
            this.location = this.datas.location;
        },
        mount() {},

        async validate() {
            const { valid } = await this.$refs.form.validate();
            // const date = moment(this.date).format("LLLL");
            // const time = moment().format("LTS");
            if (valid) {
                axios
                    .put("/edit_bag_info", {
                        id: this.datas.id,
                        seller: this.seller,
                        bag_name: this.bag_name,
                        location: this.location,
                    })
                    .then((res) => {
                        this.dialog = false;
                        this.$swal({
                            icon: "success",
                            title: "Updated!",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        this.$router.push({
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                    })
                    .catch((err) => {
                        this.dialog = false;
                        this.$swal({
                            icon: "error",
                            title: "error",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    });
            }
        },
        reset() {
            this.$refs.form.reset();
            this.dialog = false;
        },
    },
};
</script>
