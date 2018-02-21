<template>
    <form :action="path" method="POST" ref="login" target="_blank">
        <slot name="token"></slot>
        <button type="submit">
            <slot></slot>
        </button>
    </form>
</template>
<script>
export default {
    props: ['path'],
    methods: {
    }
}
</script>

