<template>
    <div class="text-center">
        <v-btn
            size="small"
            variant="outlined"
            large
            block
            color="blue"
            @click="dialog = true"
            :disabled="datas.status === 'Sold' ? true : false"
        >
            <v-icon large color="blue"> mdi-briefcase-edit-outline</v-icon>
        </v-btn>
        <v-dialog persistent v-model="dialog" width="500">
            <v-card>
                <v-card-text>
                    <v-card-title>
                        <span class="text-h5">EDIT PRICING</span>
                    </v-card-title>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <v-text-field
                            v-model="price"
                            :rules="priceRules"
                            label="Price Name"
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
        price: "",
        loading: false,
        dialog: false,
        priceRules: [(v) => !!v || "Price is required"],
    }),
    mounted() {
        this.unitId = this.$route.path.split("/")[3];
        this.eventId = this.$route.path.split("/")[4];
        this.price = this.datas.price;
    },
    methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                axios
                    .put("/edit_ticket_price_in_bag", {
                        id: this.datas.id,
                        price: this.price,
                    })
                    .then((res) => {
                        this.dialog = false;
                        this.$swal({
                            icon: "success",
                            title: "saved",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
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
