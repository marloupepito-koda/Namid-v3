<template>
    <div class="login login-v1 bg-white vh-100">
        <div class="row h-75 justify-content-center align-items-center">
            <div class="card shadow col-md-4">
                <LoginForm />
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
