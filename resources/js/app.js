/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
import { createApp } from "vue";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "@mdi/font/css/materialdesignicons.css";
import router from "./components/router/AppRoutes.js";
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "myCustomTheme",
        themes: {
            myCustomTheme: {
                dark: false,
                colors: {
                    background: "#FFFFFF",
                    surface: "#FFFFFF",
                    primary: "#0000ff",
                    "primary-darken-1": "#3700B3",
                    secondary: "#03DAC6",
                    "secondary-darken-1": "#018786",
                    error: "#B00020",
                    info: "#2196F3",
                    success: "#4CAF50",
                    warning: "#FB8C00",
                },
            },
        },
    },
});

const app = createApp({});
app.use(router);
app.use(vuetify);
app.use(VueSweetalert2);

import AppComponent from "./components/Index.vue";
app.component("app-component", AppComponent);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount("#app");
