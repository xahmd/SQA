<template>
    <div class="py-2 text-center">
        <button @click="showNewCategoryForm = !showNewCategoryForm; imagePreview[0] = null"
            class="py-2 text-sm font-semibold px-6 rounded bg-teal-500 text-white hover:bg-teal-600"><i
                class="fa-solid fa-plus"></i> Add</button>
    </div>
    <div v-if="showNewCategoryForm" class="mb-3">
        <form method="POST" action="/api/recipes_categories" enctype="multipart/form-data" class="p-3 bg-neutral-100">
            <input type="hidden" name="_token" :value="csrf_token">
            <label for="label"
                class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                <input type="text" required name="label" id="label" placeholder="Label"
                    class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                <span
                    class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                    Label
                </span>
            </label>
            <label for="description"
                class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                <input type="text" required name="description" id="description" placeholder="Description"
                    class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                <span
                    class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                    Description
                </span>
            </label>
            <div class="flex items-center justify-center w-full">
                <label for="image" @drop="drop($event)" @dragover="$event.preventDefault()"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <div v-if="imagePreview[0]" class="py-2 text-center">
                            <img :src="imagePreview[0]" alt="Image Preview" class="h-60">
                        </div>
                        <div v-else id="previewImagePlaceholder">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to
                                    upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF</p>
                        </div>
                    </div>
                    <input id="image" required name="image" type="file" class="hidden" @change="imageSelected($event, 0)" />
                </label>
            </div>
            <div class="py-2 text-center">
                <button class="bg-emerald-500 py-2 px-6 text-sm font-semibold rounded text-white">Save</button>
            </div>
        </form>
    </div>
    <table class="text-left w-full bg-white border text-sm rounded overflow-hidden shadow-md">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-2 w-12 text-center whitespace-nowrap">ID</th>
                <th class="py-2 px-4 w-[1%] whitespace-nowrap">Category</th>
                <th class="py-2 px-4 whitespace-nowrap">Description</th>
                <th class="py-2 px-4 w-[1%] whitespace-nowrap">Recipes Count</th>
                <th class="py-2 px-4 whitespace-nowrap">Featured Image</th>
                <th class="py-2 px-4 w-[1%] whitespace-nowrap">Related Links</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(category, index) in categories" :key="index" class="border-b border-gray-200">
                <td class="py-2 w-12 text-center">
                    {{ category.id }}
                </td>
                <td class="py-2 px-4 w-[1%] whitespace-nowrap">
                    {{ category.label }}
                </td>
                <td class="py-2 px-4">
                    {{ category.description }}
                </td>
                <td class="py-2 px-4 w-[1%] whitespace-nowrap text-center">
                    {{ category.recipes_count }}
                </td>
                <td class="py-2 px-4 w-40"><img
                        class="w-full h-16 object-cover border -skew-x-12 transition-all hover:skew-x-0 cursor-pointer rounded"
                        :src="category.image_url" alt="Cuisine Image"></td>
                <td class="py-2 px-4 w-[1%] whitespace-nowrap text-center">
                    <button @click="edit(category)"
                        class="inline-block py-1 px-2 bg-neutral-200 rounded hover:bg-neutral-100">Edit</button>
                    <form class="inline-block ml-2" @submit="deleteCategory($event)" v-if="category.recipes_count === 0"
                        :action="'/api/recipes_categories/' + category.id" method="POST">
                        <input type="hidden" name="_method" value="delete" />
                        <input type="hidden" name="_token" :value="csrf_token">
                        <button class="inline-block py-1 px-2 bg-red-400 rounded hover:bg-red-300">Delete</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    <div v-if="category_edit"
        :class="'fixed pt-8 top-0 left-0 w-full h-full transition-all animate-pop bg-black bg-opacity-20'">
        <div class="bg-white p-6 border shadow-lg max-w-auto w-[600px] overflow-y-auto flex-0 mx-auto rounded-lg">
            <form method="POST" enctype="multipart/form-data" :action="'/api/recipes_categories/' + category_edit.id">
                <input type="hidden" name="_method" value="put" />
                <input type="hidden" name="_token" :value="csrf_token">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label for="label"
                            class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                            <input type="text" name="label" id="label" placeholder="Label" v-model="category_edit.label"
                                class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                            <span
                                class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                                Label
                            </span>
                        </label>
                        <label for="description"
                            class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                            <input type="text" name="description" id="description" placeholder="Description"
                                v-model="category_edit.description"
                                class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                            <span
                                class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                                Description
                            </span>
                        </label>
                    </div>
                    <div class="pb-2">
                        <img :src="category_edit.image_url" alt="Image">
                    </div>
                </div>
                <div class="flex items-center justify-center w-full">
                    <label for="image1" @drop="drop($event)" @dragover="$event.preventDefault()"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <div v-if="imagePreview[1]" class="py-2 text-center">
                                <img :src="imagePreview[1]" alt="Image Preview" class="h-60">
                            </div>
                            <div v-else id="previewImagePlaceholder">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to
                                        upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF</p>
                            </div>
                        </div>
                        <input id="image1" name="image" type="file" class="hidden" @change="imageSelected($event, 1)" />
                    </label>
                </div>
                <div class="py-2 text-center">
                    <button
                        class="py-2 px-4 rounded mr-2 bg-teal-700 hover:bg-teal-600 active:bg-teal-700 text-white">Save</button>
                    <button class="py-2 px-4 rounded bg-slate-700 hover:bg-slate-600 active:bg-slate-700 text-white"
                        @click="category_edit = null; imagePreview[1] = null">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    name: "RecipesCategories",
    data() {
        return {
            categories: [],
            showNewCategoryForm: false,
            imagePreview: [null, null],
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            category_edit: null,
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
        },
        imageSelected(event, index) {
            const ref = this
            const input = event.target;
            if (input.files.length > 0) {
                const file = input.files[0]
                const reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onload = function () {
                    ref.imagePreview[index] = reader.result
                };
                reader.onerror = function (error) {
                    console.log('Error: ', error);
                };
            }
            else {
                this.imagePreview[index] = null
            }
        },
        drop(ev) {
            ev.preventDefault();
            if (ev.dataTransfer.files) {
                const input = ev.currentTarget.querySelector("input[name='image'][type='file']");
                input.files = ev.dataTransfer.files;
                input.dispatchEvent(new Event("change"));
            }
        },
        edit(category) {
            this.category_edit = category
        },
        deleteCategory(event) {
            const d = confirm("Delete ?");
            d ? d : event.preventDefault()
        }
    }
}
</script>