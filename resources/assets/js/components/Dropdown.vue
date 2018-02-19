<template>
    <div class="inline-block">
        <div class="relative cursor-pointer z-10" @mouseover="showing=true" @mouseout="showing = false">
            <div class="relative z-10">
                <slot name="button"></slot>
            </div>
            <div
                v-show="showing"
                class="absolute pin-t pin-r mt-2 mr-2 w-48 bg-grey-light text-black p-4 rounded-lg"
            >
                <h3 v-if="heading" class="text-sm uppercase tracking-wide mb-3" v-text="heading">My Links</h3>
                <ul class="list-reset">
                    <slot name="menu"></slot>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['heading'],
        data() {
            return {
                showing: false
            }
        }
    }
</script>
