<template>
    <div class="container card mt-5">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="col-md-12">
                    <div id="over">
                        <div class="green-label text-uppercase">
                            North American Midway Entertainment
                        </div>
                        <div id="payment">
                            <div class="text-white pt-2">
                                Ticket Inventory Management System
                            </div>
                            <div class="text-justify text-lighter">
                                We cannot accept payment on your website.
                            </div>
                        </div>
                        <div id="rupee">
                            <span>&#8377;</span>
                        </div>
                    </div>
                    <br /><br /><br />
                    <div class="text-justify py-3 mt-5">
                        North American Midway Entertainment is an American
                        provider of midway services based in Farmland, Indiana
                        that describes itself as "the world's largest traveling
                        outdoor amusement park.
                    </div>
                    <div class="d-flex align-items-center pb-4">
                        <a href="#"
                            >Learn More
                            <span
                                class="fas fa-arrow-right text-primary"
                            ></span>
                        </a>
                    </div>
                    <div class="h6 py-2 pt-4 font-weight-bold mt-5">
                        Inventory
                    </div>
                    <div class="text-justify pb-3">
                        Inventory management of tickets and only allowing sales
                        for items in inventory (and prohibiting duplicate ticket
                        numbers from being entered) is reason for needing a new
                        ticketing system.
                    </div>
                    <div class="d-flex align-items-center pb-4">
                        <a href="#"
                            >Learn More
                            <span
                                class="fas fa-arrow-right text-primary"
                            ></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="col-md-12">
                    <div
                        class="card bg-md-white d-flex flex-column align-items-between"
                    >
                        <div class="form container">
                            <div class="h4 font-weight-bold text-center mb-4">
                                LOGIN TO DASHBOARD
                            </div>
                            <LoginForm />
                        </div>
                        <div class="text-center text-muted mt-auto">
                            Need help? <span><a href="#">Contact Us</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoginForm from "./content/LoginForm.vue";
export default {
    components: {
        LoginForm,
    },
};
</script>
