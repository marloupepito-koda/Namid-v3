<template>
    <v-container style="max-width: 600px">
        <div class="row">
            <div class="col-md-10 col-10 pr-0">
                <Datepicker
                    :enable-time-picker="false"
                    class="mb-3"
                    placeholder="Select Date"
                    v-model="date"
                    input-class-name="dp-custom-input"
                ></Datepicker>
            </div>
            <div class="col-md-2 col-2 pl-0">
                <v-btn @click="changeDate" color="black">Search</v-btn>
            </div>
        </div>

        <v-text-field
            v-model="search"
            label="Search transactions"
            required
            variant="outlined"
            density="compact"
            @input="searchData"
        ></v-text-field>
        <v-timeline density="compact" side="end">
            <v-timeline-item class="mb-6" hide-dot>
                <span>TODAY</span>
            </v-timeline-item>

            <v-timeline-item
                v-for="i in getData"
                class="mb-4"
                dot-color="black"
                size="small"
            >
                <div class="d-flex justify-space-between flex-grow-1">
                    <div>
                        <!-- <v-chip class="ms-0" color="blue" label size="small">
                            {{
                                i.created_at.substring(0, 19).replace("T", " ")
                            }}
                        </v-chip> -->
                        <v-chip class="ms-0" color="blue" label size="small">
                            {{
                                i.date +
                                " " +
                                new Date(i.created_at).getHours() +
                                ":" +
                                new Date(i.created_at).getMinutes() +
                                " " +
                                (new Date(i.created_at).getHours() >= 12
                                    ? "PM"
                                    : "AM")
                            }}
                        </v-chip>
                        <br />
                        {{ i.descriptions }}
                    </div>
                </div>
            </v-timeline-item>
        </v-timeline>
        <v-btn
            v-if="count !== getData.length"
            color="black"
            :loading="loading"
            size="small"
            class="offset-md-5"
            @click="seeMore"
            >see more</v-btn
        >
    </v-container>
</template>

<script>
import axios from "axios";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import moment from "moment";
export default {
    components: { Datepicker },
    data: () => ({
        date: null,
        data: [],
        input: null,
        nonce: 0,
        unitId: "",
        unitName: "",
        eventName: "",
        eventId: "",
        search: [],
        getData2: [],
        getData: [],
        limit: 10,
        loading: false,
        count: "",
    }),

    mounted() {
        this.mount();
    },
    methods: {
        changeDate() {
            this.mount();
        },
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
        seeMore() {
            this.loading = true;
            this.limit = this.limit + 10;
            axios
                .get(
                    "/api/get_event_logs/" +
                        [
                            this.unitId,
                            this.eventId,
                            this.limit,
                            this.date === null
                                ? moment().format("MM-DD-YYYY")
                                : moment(new Date(this.date)).format(
                                      "MM-DD-YYYY"
                                  ),
                        ]
                )
                .then((res) => {
                    this.loading = false;
                    this.getData2 = Object.values(res.data.status);
                    this.getData = Object.values(res.data.status);

                    let urls = [
                        "/api/get_event_logs/" +
                            [
                                this.unitId,
                                this.eventId,
                                this.limit,
                                this.date === null
                                    ? moment().format("MM-DD-YYYY")
                                    : moment(new Date(this.date)).format(
                                          "MM-DD-YYYY"
                                      ),
                            ],
                    ];
                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                });
        },
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.unitName = this.$route.path.split("/")[4].replace(/_/g, " ");
            this.eventName = this.$route.path.split("/")[5].replace(/_/g, " ");
            this.eventId = this.$route.query.event_id;
            axios
                .get(
                    "/api/get_event_logs/" +
                        [
                            this.unitId,
                            this.eventId,
                            this.limit,
                            this.date === null
                                ? moment().format("MM-DD-YYYY")
                                : moment(new Date(this.date)).format(
                                      "MM-DD-YYYY"
                                  ),
                        ]
                )
                .then((res) => {
                    let urls = [
                        "/api/get_event_logs/" +
                            [
                                this.unitId,
                                this.eventId,
                                this.limit,
                                this.date === null
                                    ? moment().format("MM-DD-YYYY")
                                    : moment(new Date(this.date)).format(
                                          "MM-DD-YYYY"
                                      ),
                            ],
                    ];
                    caches.open("static_cache").then((cache) => {
                        cache.addAll(urls).then(() => {
                            console.log("Data cached ");
                        });
                    });
                    this.getData2 = Object.values(res.data.status);
                    this.getData = Object.values(res.data.status);
                    this.count = res.data.count;
                });
        },
    },
};
</script>
