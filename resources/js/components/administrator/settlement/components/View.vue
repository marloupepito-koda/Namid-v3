<template>
    <div class="container">
        <div v-if="hideInvoice === true">
            <div class="invoice-company">
                <strong>North American Midway Entertainment</strong>
            </div>

            <div class="invoice-header">
                <div class="invoice-from"></div>
                <div class="invoice-to">
                    <address class="mt-5px mb-5px"></address>
                </div>
                <div class="invoice-date">
                    <div class="date text-dark mt-5px">{{ date }}</div>
                    <div class="invoice-detail">Date</div>
                </div>
            </div>
            <div class="invoice-content mt-5">
                <div class="table-responsive">
                    <table class="table table-invoice">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>Concessions</td>
                                <td class="text-center">
                                    <a
                                        v-if="hide1 === true"
                                        href="javascript:;"
                                        class="text-decoration-none fw-bold"
                                        @click="concessionsClick"
                                        ><i class="fa fa-plus fa-fw"></i>
                                        <div v-if="concessions === 0">
                                            Add Amount
                                            <v-icon
                                                icon="mdi-square-edit-outline"
                                            ></v-icon>
                                        </div>
                                        <div v-else>
                                            $
                                            {{
                                                String(
                                                    parseInt(
                                                        concessions
                                                    ).toFixed(2)
                                                ).replace(
                                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                    "$1,"
                                                )
                                            }}
                                        </div>
                                    </a>
                                    <input
                                        v-else
                                        type="number"
                                        class="form-control form-control-xs"
                                        v-model="concessions"
                                        v-on:keyup.enter="concessionsValue"
                                    />
                                    <!-- <a
                                        v-else
                                        href="javascript:;"
                                        @click="concessionsCancel"
                                        class="text-decoration-none fw-bold"
                                        >{{ concessions }} &nbsp;&nbsp;&nbsp;<i
                                            class="fa fa-pen fa-fw"
                                        ></i
                                    ></a> -->
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <span class="text-dark">Miscellaneous</span>
                                </td>
                                <td class="text-center">
                                    <a
                                        v-if="hide2 === true"
                                        href="javascript:;"
                                        class="text-decoration-none fw-bold"
                                        @click="miscellaneousClick"
                                    >
                                        <div v-if="miscellaneous === 0">
                                            Add Amount
                                            <v-icon
                                                icon="mdi-square-edit-outline"
                                            ></v-icon>
                                        </div>
                                        <div v-else>
                                            $
                                            {{
                                                String(
                                                    parseInt(
                                                        miscellaneous
                                                    ).toFixed(2)
                                                ).replace(
                                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                    "$1,"
                                                )
                                            }}
                                        </div>
                                    </a>
                                    <input
                                        v-else
                                        class="form-control form-control-xs"
                                        v-model="miscellaneous"
                                        v-on:keyup.enter="miscellaneousValue"
                                    />
                                    <!-- <a
                                        v-else
                                        href="javascript:;"
                                        @click="miscellaneousCancel"
                                        class="text-decoration-none fw-bold"
                                        >{{
                                            miscellaneous
                                        }}
                                        &nbsp;&nbsp;&nbsp;<i
                                            class="fa fa-pen fa-fw"
                                        ></i
                                    ></a> -->
                                </td>
                                <td class="text-center"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td width="30%">
                                    Total Amount Of Tickets Sold
                                </td>
                                <td width="30%" class="text-center">
                                    $
                                    {{
                                        String(
                                            parseInt(ticketPrice).toFixed(2)
                                        ).replace(
                                            /(\d)(?=(\d\d\d)+(?!\d))/g,
                                            "$1,"
                                        )
                                    }}
                                </td>
                                <td class="text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="invoice-price">
                    <div class="invoice-price-left">
                        <div class="invoice-price-row offset-md-2">
                            <div class="sub-price">
                                <span class="text-dark">Balance</span>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="sub-price">
                                <i class="fa fa-plus text-muted"></i>
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="sub-price h5">
                                <strong>Total Balance</strong>
                                <span class="text-dark ml-5"
                                    >$
                                    {{
                                        isNaN(
                                            parseInt(concessions) +
                                                parseInt(miscellaneous) +
                                                parseInt(ticketPrice)
                                        ) === true
                                            ? "Calculating..."
                                            : String(
                                                  parseInt(
                                                      parseInt(concessions) +
                                                          parseInt(
                                                              miscellaneous
                                                          ) +
                                                          parseInt(ticketPrice)
                                                  ).toFixed(2)
                                              ).replace(
                                                  /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                  "$1,"
                                              )
                                    }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <b>
                {{ notify }}
            </b>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
export default {
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
        mount() {
            if (this.$route.query.eventid !== undefined) {
                this.notify = "Searching...";
                axios
                    .get(
                        "/api/get_committee_settlement/" +
                            this.$route.query.unitid +
                            "/" +
                            this.$route.query.eventid
                    )
                    .then((res) => {
                        console.log("ssss", res.data.status);
                        if (res.data.status.length !== 0) {
                            this.notify = "";
                            this.hideInvoice = true;
                            const ticket = res.data.status
                                .map(
                                    (a) =>
                                        parseFloat(a.quantity) *
                                        parseFloat(a.price)
                                )
                                .reduce((partialSum, a) => partialSum + a, 0);
                            this.ticketPrice = ticket;
                        } else {
                            this.notify = "Not Found!";
                            this.hideInvoice = false;
                        }
                    });
            } else {
                this.hideInvoice = false;
            }
        },
        concessionsClick() {
            this.hide1 = false;
        },

        miscellaneousClick() {
            this.hide2 = false;
        },
        concessionsValue() {
            this.hide1 = true;
        },
        miscellaneousValue(e) {
            this.hide2 = true;
        },

        // miscellaneousCancel() {
        //     this.miscellaneous = "";
        // },
    },
    data() {
        return {
            hide1: true,
            hide2: true,
            ticketPrice: "",
            notify: "Please Search Settlement",
            total: 0,
            hideInvoice: false,
            concessions: 0,
            miscellaneous: 0,
            date: moment(new Date()).format("LLLL"),
        };
    },
};
</script>

<style>
td.in-range,
td.weekend.in-range {
    border-color: #ff9999 !important;
    background-color: #ff9999 !important;
    color: white !important;
}
td.weekend.active.in-range.end-date,
td.weekend.active.in-range.start-date,
td.active.in-range.end-date,
td.active.in-range.start-date {
    border-color: #ff0000 !important;
    background-color: #ff0000 !important;
    color: white !important;
}
</style>
