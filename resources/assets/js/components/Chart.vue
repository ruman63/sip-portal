<template>
    <div>
        <canvas ref="canvas" class="w-full"></canvas>
    </div>
</template>
<script>
import Chart from 'chart.js';
import pallete from '../palette';
export default {
    props: ['type', 'values', 'labels'],
    data() {
        return {
        }
    },
    computed: {
        colors() {
            return pallete('tol', this.values.length)
                .map(hex => `#${hex}`);
        }
    },
    mounted() {
        const chart = new Chart(this.$refs.canvas.getContext('2d'),{
            type: this.type,
            data: {
                datasets: [{ 
                    data: this.values,
                    backgroundColor: this.colors
                }],
                labels: this.labels
            },
            options: {
                responsive: true,
            }
        });
    }
    
}
</script>

