<template>
    <div class="bg-white p-6 animate-push">
        <h2 class="mb-4 text-2xl font-semibold">Categories</h2>
        <div class="flex p-1 rounded bg-neutral-100 w-[600px] mx-auto">
            <div class="w-1/2 p-1 realative">
                <router-link to="/categories/recipes" class="group">
                    <span class="block py-2 px-1 text-center rounded cursor-pointer transition-all bg-neutral-200 hover:scale-95 group-[.active]:bg-yellow-300 group-[.active]:text-neutral-600">Recipes Categories</span>
                </router-link>
            </div>
            <div class="w-1/2 p-1 realative">
                <router-link to="/categories/articles" class="group">
                    <span class="block py-2 px-1 text-center rounded cursor-pointer transition-all bg-neutral-200 hover:scale-95 group-[.active]:bg-yellow-300 group-[.active]:text-neutral-600">Articles Categories</span>
                </router-link>
            </div>
        </div>
        <div class="mt-3">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>

export default {
    name: "Categories",
    data() {
        return {
            categories: []
        }
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            fetch("/api/recipes_categories")
                .then(res => res.json())
                .then(data => { this.categories = data })
        }
    }
}
</script>