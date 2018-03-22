<template>
    <li>
        <a href="#" @click.prevent="toggle"> 
            <span class="flex-1">
                <slot></slot>
            </span> 
            <i class="ml-1 fa" :class="icon"></i>
        </a>
        <ul v-show="isOpen" class="menu mt-1">
            <slot name="items"></slot>
        </ul>
    </li>
</template>
<script>
export default {
    data() {
        return {
            isOpen: false
        }
    },
    computed: {
        icon() {
            return 'fa-caret-' + ( this.isOpen ? 'down' : 'right' );
        }
    },
    methods: {
        toggle() {
            this.isOpen = !this.isOpen;
        }
    }
}
</script>
