<template>
    <div>
        <v-btn @click="editTickets" color="black" size="x-small" icon>
            <v-icon>mdi-pencil-outline</v-icon>
        </v-btn>
        <v-dialog v-model="showModal" max-width="500">
            <v-card>
                <v-card-title>
                    <span class="headline">Edit Ticket Sold</span>
                    <v-spacer></v-spacer>
                </v-card-title>

                <v-card-text>
                    <v-form ref="form" v-model="valid" lazy-validation>
                        <div class="row">
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="start"
                                    :rules="startRules"
                                    label="Starting #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                            <div class="col-md-6">
                                <v-text-field
                                    v-model="end"
                                    :rules="endRules"
                                    label="Ending #"
                                    required
                                    variant="outlined"
                                    density="compact"
                                ></v-text-field>
                            </div>
                        </div>

                        <div>
                            <div class="row p-3">
                                <div class="col-md-6 col-6 offset-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <v-btn
                                                color="error"
                                                class="mr-4"
                                                @click="reset"
                                            >
                                                CANCEL
                                            </v-btn>
                                        </div>
                                        <div class="col-md-6">
                                            <v-btn
                                                color="black"
                                                class="mr-4"
                                                @click="validate"
                                                :disabled="
                                                    parseInt(start) >
                                                    parseInt(end)
                                                        ? true
                                                        : false
                                                "
                                            >
                                                SUBMIT
                                            </v-btn>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props: ["datas"],
    data() {
        return {
            showModal: false,
            start: "",
            start2: "",
            end: "",
            end2: "",
            id: "",
            valid: false,
            startRules: [(v) => !!v || "Starting # is required"],
            endRules: [(v) => !!v || "Starting # is required"],
        };
    },
    methods: {
        editTickets() {
            this.showModal = true;
            this.start = this.datas.start;
            this.end = this.datas.end;
            this.start2 = this.datas.start;
            this.end2 = this.datas.end;
            this.id = this.datas.id;
        },
        async validate() {
            const { valid } = await this.$refs.form.validate();
            if (valid) {
                axios
                    .put("/edit_ticket_sold", {
                        id: this.id,
                        start: this.start,
                        end: this.end,
                    })
                    .then((res) => {
                        this.$router.push({
                            path: this.$route.path,
                            hash: "#" + Math.floor(Math.random() * 999999),
                        });
                        this.$swal({
                            icon: "success",
                            title: "Updated Successfully",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        this.reset();
                    });
            }
        },
        reset() {
            this.showModal = false;
            this.loading = false;
            this.$refs.form.reset();
        },
    },
};
</script>
