<template>
    <a href="#" @click.prevent="logout">
        <slot>Logout</slot>
    </a>
</template>
<script>
export default {
    props: {
        path: {'required': true}
    },
    methods: {
        logout() {
            axios.post(this.path)
                .then(() => location.reload());
        }
    }
}
</script>

