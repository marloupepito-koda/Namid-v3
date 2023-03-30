<template></template>

<script>
export default {
    props: ["icon", "title"],

    mounted() {
        return this.$swal({
            icon: this.icon,
            title: this.title,
            showConfirmButton: false,
            timer: 1500,
        });
    },
};
</script>
