<template>
    <div class="bg-white p-6 animate-push">
        <!-- Dashboard -->
        <div class="flex flex-wrap">
            <div class="p-3 w-48 max-w-full rounded mr-2 mb-2 bg-white border shadow">
                <div class="py-1 font-bold text-neutral-700">Posts Count</div>
                <div class="text-2xl py-1 text-amber-400">{{ data?.posts_count }}</div>
            </div>
            <div class="p-3 w-48 max-w-full rounded mr-2 mb-2 bg-white border shadow">
                <div class="py-1 font-bold text-neutral-700">Recipes Count</div>
                <div class="text-2xl py-1 text-blue-400">{{ data?.recipes_count }}</div>
            </div>
            <div class="p-3 w-48 max-w-full rounded mr-2 mb-2 bg-white border shadow">
                <div class="py-1 font-bold text-neutral-700">Articles Count</div>
                <div class="text-2xl py-1 text-green-400">{{ data?.articles_count }}</div>
            </div>
        </div>
        <div v-if="data">
            <div v-for="(v, index) in total()" :key="index">
                {{ find(index) }}
            </div>
            <div>
                <canvas ref="chart_el"></canvas>
            </div>
        </div>
    </div>
</template>
<script>
import Chart from 'chart.js/auto';
import { ref } from "vue";
const chart_el = ref(null)
export default {
    name: "Dashboard",
    data() {
        return {
            data: null,
        }
    },
    methods: {
        fetchData() {
            fetch("/api/dashboard").then(r => r.json()).then(r => this.data = r).then(r => this.total)
        },
        async date(e = null) {
            return e ? new Date(e) : new Date();
        },
        async formatDate(date) {
            return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate()
        },
        async minDate() {
            return await (this.data ? this.date(this.data.website_views.reduce((prev, curr) => prev.id < curr.id ? prev : curr).viewed_at) : this.date())
        },
        async total() {
            return this.data ? Math.floor(((await this.date()).getTime() - (await this.minDate()).getTime()) / 86400000) : 0
        },
        async find(index) {
            let d = await this.formatDate(this.date(this.date().setDate(this.date().getDate() - index)))
            let f = await this.data.website_views.find(x => (this.formatDate(this.date(x.viewed_at)) == d))
            return await (f ? { "views_count": f.views_count, "viewed_at": f.viewed_at } : { "views_count": 0, "viewed_at": d })
        },
        async setup_data(index = 0, value = [], labels = []){
            const total = this.total();
            if (index < total) {
                const element = await this.find(index)
                return await this.setup_data(index + 1, [...value, element.views_count], [...labels, element.viewed_at])
            }
            return {'values': value, 'labels': labels}
        }
    },
    async mounted() {
        this.fetchData();
        //const ctx = document.getElementById('example');
        const data = this.setup_data();
        const exampleChart = new Chart(chart_el, {
            type: 'line',
            data: [],
            options: {},
        });
    },
}
</script>