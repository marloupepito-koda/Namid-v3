<template>
    <div>
        <div class="h1 text-center align-center">Please wait!</div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {};
    },
    mounted() {
        axios
            .post("/ee_user_logout")
            .then((res) => {
                // localStorage.clear();
                this.$router.push({ path: "/auth" });
            })
            .catch((err) => {});
    },
};
</script>
