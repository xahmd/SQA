<template>
    <div class="bg-white p-6 animate-push">
        <!-- Articles -->
        <h2 class="mb-4 text-2xl font-semibold">All Articles <router-link to="/articles/create" class="float-right py-2 px-5 rounded bg-teal-500 text-base text-white hover:bg-teal-600">Add</router-link>
        </h2>
        <table class="multiple-check-handle bg-neutral-100 w-full text-sm overflow-hidden rounded-lg shadow-[0_1px_5px_-1px_rgba(0,0,0,.2)]">
            <thead>
                <tr class="text-left bg-slate-200 font-bold">
                    <td class="flex items-center justify-center py-3 w-9 text-center">
                        <input type="checkbox" name="all" class="cursor-pointer w-4 h-4 accent-amber-500">
                    </td>
                    <td class="px-3 py-2">Title</td>
                    <td class="px-3 py-2">Category</td>
                    <td class="px-3 py-2">Created at</td>
                    <td class="px-3 py-2">Views</td>
                    <td class="px-3 py-2">Action</td>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b" v-for="(article, index) in articles" :key="index">
                    <td class="flex items-center justify-center py-3 w-9 text-center">
                        <input type="checkbox" name="1" class="cursor-pointer w-4 h-4 accent-amber-500">
                    </td>
                    <td class="px-3 py-2">{{ article.title }}</td>
                    <td class="px-3 py-2 italic">
                        <a v-for="(category, index) in article.categories" :key="index" href="#" class="hover:underline mr-1">{{ category.label }}</a>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">{{ (new Date((article.created_at))).toLocaleString() }}</td>
                    <td class="px-3 py-2 whitespace-nowrap">341<i class="ml-1 fa-regular fa-eye"></i></td>
                    <td class="px-3 py-1 whitespace-nowrap">
                        <router-link :to="'/articles/edit/' + article.id" class="bg-neutral-200 rounded py-1 px-2 hover:bg-neutral-300 text-neutral-700 mr-1"><i class="fa-solid fa-pen"></i></router-link>
                        <button @click="destroy(recipe.id)" class="bg-neutral-200 rounded py-1 px-2 hover:bg-neutral-300 text-neutral-700"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
            <tfoot class="hidden">
                <tr>
                    <td colspan="6" class="px-3 py-2 bg-slate-200">
                        <span>Multiple Action: </span>
                        <button class="bg-neutral-300 ml-1 rounded py-1 px-2 hover:bg-neutral-400 text-neutral-700"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
export default {
    name: "Articles",
    data() {
        return {
            articles: []
        }
    },
    mounted() {
        this.fetchArticles();
    },
    methods: {
        destroy(id) {
            const fd = new FormData()
            fd.append("_method", "delete")
            fd.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'))
            fetch("/api/articles/" + id, {
                method: "POST",
                body: fd
            }).then(() => this.$router.go())
        },
        fetchArticles() {
            fetch("/api/articles")
                .then(res => res.json())
                .then(data => { this.articles = data })
        }
    }
}
</script>