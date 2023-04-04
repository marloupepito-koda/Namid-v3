<template>
    <div class="container">
        <div v-if="hideInvoice === true">
            <div>
                <div class="invoice-company">
                    <strong>North American Midway Entertainment</strong>
                </div>
                <div class="row mt-5">
                    <div class="col-md-4 col-4">
                        <small>from</small>
                        <address class="mt-5px mb-5px">
                            <strong class="text-dark">{{
                                eventData.units_fullname
                            }}</strong
                            ><br />
                            {{ dateNow }}<br />
                        </address>
                    </div>
                    <div class="col-md-4 col-4 text-center">
                        <address class="mt-5px mb-5px">
                            <strong class="text-dark">Ticket Sales</strong
                            ><br />
                            Daily Seller Breakdown<br />
                        </address>
                    </div>
                    <div class="col-md-4 col-4">
                        <div class="date text-dark mt-5px text-left align-left">
                            {{ start }} to
                            {{ end }}
                        </div>
                        <div class="invoice-detail text-left align-left">
                            Average Ticket Price &nbsp;&nbsp;$ {{ atp }}<br />
                        </div>
                    </div>
                </div>
                <div class="invoice-content mt-5">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="20%">Seller</th>
                                    <th class="text-center" width="10%">
                                        Ticket Type
                                    </th>
                                    <th class="text-center" width="10%">
                                        Starting #
                                    </th>
                                    <th class="text-end" width="10%">
                                        Ending #
                                    </th>
                                    <th class="text-end" width="10%">Sold</th>
                                    <th class="text-end" width="10%">Price</th>
                                    <th class="text-end" width="10%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in soldData"
                                    :key="index"
                                >
                                    <td>{{ item.seller }}</td>
                                    <td class="text-center">
                                        {{ item.ticket_type }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            String(item.start).replace(
                                                /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                "$1,"
                                            )
                                        }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            String(item.end).replace(
                                                /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                "$1,"
                                            )
                                        }}
                                    </td>
                                    <td class="text-end">
                                        {{
                                            String(
                                                item.end - item.start + 1
                                            ).replace(
                                                /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                "$1,"
                                            )
                                        }}
                                    </td>

                                    <!-- <td class="text-end">$2,500.00</td> -->
                                    <td class="text-end">
                                        $
                                        {{
                                            String(item.price).replace(
                                                /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                "$1,"
                                            )
                                        }}
                                    </td>
                                    <td class="text-end">
                                        $
                                        {{
                                            String(
                                                parseFloat(item.price) *
                                                    (parseFloat(item.end) -
                                                        parseFloat(item.start) +
                                                        1)
                                            ).replace(
                                                /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                "$1,"
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 offset-md-8">
                        <!-- END table-responsive -->
                        <div class="invoice-price bg-white">
                            <div class="invoice-price-right">
                                <div class="invoice-price-row">
                                    <div class="sub-price h3">
                                        <b class="text-dark">TOTAL: </b>
                                        <div class="text-dark float-right">
                                            $
                                            {{
                                                String(total).replace(
                                                    /(\d)(?=(\d\d\d)+(?!\d))/g,
                                                    "$1,"
                                                )
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invoice-footer mt-5">
                    <p class="text-center mb-5px fw-bold">
                        THANK YOU FOR YOUR BUSINESS
                    </p>
                    <p class="text-center">
                        <span class="me-10px"
                            ><i class="fa fa-fw fa-lg fa-globe"></i>
                            matiasgallipoli.com</span
                        >
                        <span class="me-10px"
                            ><i class="fa fa-fw fa-lg fa-phone-volume"></i>
                            T:016-18192302</span
                        >
                        <span class="me-10px"
                            ><i class="fa fa-fw fa-lg fa-envelope"></i>
                            namid.event-essentials.net</span
                        >
                    </p>
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
            if (this.$route.query.end !== undefined) {
                this.notify = "Searching...";
                axios
                    .get(
                        "/api/search_spot_transaction/" +
                            this.$route.query.unitid +
                            "/" +
                            this.$route.query.eventid +
                            "/" +
                            moment(this.$route.query.start).format(
                                "MM-DD-YYYY" +
                                    "/" +
                                    moment(this.$route.query.end).format(
                                        "MM-DD-YYYY"
                                    )
                            )
                    )
                    .then((res) => {
                        this.start = this.$route.query.start;
                        this.end = this.$route.query.end;
                        if (res.data.status.length !== 0) {
                            const uniqueProducts = res.data.sold.filter(
                                (obj, index, self) => {
                                    return (
                                        index ===
                                        self.findIndex((t) => t.id === obj.id)
                                    );
                                }
                            );

                            this.notify = "";
                            this.eventData = res.data.transaction;
                            this.soldData = uniqueProducts;
                            this.hideInvoice = true;

                            console.log(uniqueProducts);
                            const amount = uniqueProducts
                                .map(
                                    (aa) =>
                                        parseFloat(aa.price) *
                                        (parseFloat(aa.end) -
                                            parseFloat(aa.start) +
                                            1)
                                )
                                .reduce((partialSum, a) => partialSum + a, 0);
                            this.total = amount;
                            const price = uniqueProducts
                                .map((aa) => parseFloat(aa.price))
                                .reduce((partialSum, a) => partialSum + a, 0);
                            this.atp = parseFloat(
                                parseFloat(price) / this.soldData.length
                            );
                        } else {
                            this.soldData = [];
                            this.eventData = [];
                            this.notify = "Not Found!";
                        }
                    })
                    .catch((err) => {});
            } else {
                this.hideInvoice = false;
            }
        },
    },
    data() {
        return {
            dateNow: moment(new Date()).format("LLLL"),
            start: "",
            end: "",
            atp: "",
            units: [],
            events: [],
            unitName: "",
            eventName: "",
            eventData: [],
            soldData: [],
            notify: "Please Search Transaction",
            total: null,
            hideInvoice: false,
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
