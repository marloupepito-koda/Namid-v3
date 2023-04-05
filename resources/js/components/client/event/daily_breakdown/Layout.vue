<template>
    <div class="ml-3">
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <th scope="row" colspan="7"></th>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="6"></th>
                            <th scope="row">Seller Total:</th>
                            <th scope="row">1222</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import Tabs from "./components/Tabs.vue";
export default {
    components: {
        Tabs,
    },
};
</script>
