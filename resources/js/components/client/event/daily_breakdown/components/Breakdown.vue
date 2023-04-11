<template>
    <div>
        <h3><strong>Daily Breakdown</strong></h3>
        <div class="row text-center">
            <div class="col-md-12"><h3>NORTH AMERICAN MIDWAY ENT. 5</h3></div>
            <div class="col-md-4">
                <div class="col-md-12">WARE RD & EXPWY83</div>
                <div class="col-md-12">Date Now</div>
                <div class="col-md-12"></div>
            </div>
            <div class="col-md-4">
                <div class="col-md-12">
                    <b>TICKET SALES</b>
                </div>
                <div class="col-md-12">Daily Seller Breakdown</div>
            </div>
            <div class="col-md-4">Average Ticket Price 1.3817</div>
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Seller</th>
                            <th scope="col">Ticket Type</th>
                            <th scope="col">Starting #</th>
                            <th scope="col">Ending #</th>
                            <th scope="col">Sold #</th>
                            <th scope="col">Count</th>
                            <th scope="col">Price</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(i, key) in sales">
                            <td>
                                <b> {{ i.seller[0].seller }}</b>
                            </td>
                            <td colspan="7">
                                <table class="table table-hover">
                                    <thead>
                                        <tr v-for="ii in i.seller">
                                            <td width="18%">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <Edit :datas="ii" />
                                                    </div>
                                                    <div class="col-md-8">
                                                        {{ ii.ticket_type }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="15%">
                                                {{ ii.start }}
                                            </td>
                                            <td width="18%">
                                                {{ ii.end }}
                                            </td>
                                            <td width="12%">
                                                {{ ii.quantity }}
                                            </td>
                                            <td width="13%">
                                                {{ ii.count }}
                                            </td>
                                            <td width="12%">
                                                {{ ii.price }}
                                            </td>
                                            <td>
                                                <b>{{
                                                    (
                                                        parseFloat(ii.price) *
                                                        parseFloat(ii.quantity)
                                                    ).toLocaleString("de-DE", {
                                                        style: "currency",
                                                        currency: "USD",
                                                    })
                                                }}</b>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </td>
                            <td colspan="7">
                                <b>
                                    {{
                                        totalPrice[
                                            key
                                        ].totalPrice.toLocaleString("de-DE", {
                                            style: "currency",
                                            currency: "USD",
                                        })
                                    }}</b
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-md-4 offset-md-8 bg-black">
                    <h3 class="border">
                        Daily Total:
                        <b>
                            {{
                                dailyTotal.toLocaleString("de-DE", {
                                    style: "currency",
                                    currency: "USD",
                                })
                            }}</b
                        >
                    </h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Tabs from "./Tabs.vue";
import Edit from "./Edit.vue";
import axios from "axios";
import moment from "moment";
export default {
    data: () => ({
        unitId: true,
        eventId: [],
        date: "",
        sales: [],
        totalPrice: [],
        dailyTotal: "",
    }),
    components: {
        Tabs,
        Edit,
    },
    created() {
        this.$watch(
            () => this.$route.params,
            (toParams, previousParams) => {
                this.mount();
            }
        );
    },

    mounted() {
        this.mount();
    },
    methods: {
        mount() {
            this.unitId = this.$route.path.split("/")[3];
            this.eventId = this.$route.path.split("/")[4];
            this.date = moment().format("MM-DD-YYYY");
            this.getEventSeller();
        },
        getEventSeller() {
            axios
                .get(
                    "/api/getEventSeller/" +
                        this.unitId +
                        "/" +
                        this.eventId +
                        "/" +
                        this.date
                )
                .then((res) => {
                    const uniqueNames = [
                        ...new Set(res.data.status.map((obj) => obj.seller)),
                    ];
                    this.sales = Object.values(uniqueNames).map((seller) => ({
                        seller: res.data.status.filter(
                            (obj) => obj.seller === seller
                        ),
                    }));
                    const uniqueAmount = [
                        ...new Set(res.data.status.map((obj) => obj.seller)),
                    ];

                    const totalPrice = uniqueAmount.map((aaa, i) => ({
                        totalPrice: this.sales[i].seller
                            .map(
                                (bbb) =>
                                    parseFloat(Object.values(bbb)[8]) *
                                    parseFloat(Object.values(bbb)[12])
                            )
                            .reduce((accumulator, currentValue) => {
                                return accumulator + currentValue;
                            }, 0),
                    }));
                    this.totalPrice = totalPrice;
                    this.dailyTotal = res.data.status
                        .map(
                            (aaa) =>
                                parseFloat(aaa.quantity) * parseFloat(aaa.price)
                        )
                        .reduce((accumulator, currentValue) => {
                            return accumulator + currentValue;
                        }, 0);
                });
        },
    },
};
</script>
