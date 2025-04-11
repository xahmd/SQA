<template>
    <div class="bg-white p-6 animate-push">
        <h1 class="text-3xl font-semibold bg-white p-3">Ingredients</h1>
        <div class="py-2 text-center">
            <button @click="showNewIngredientForm = !showNewIngredientForm; imagePreview[0] = null"
                class="py-2 text-sm font-semibold px-6 rounded bg-teal-500 text-white hover:bg-teal-600"><i
                    class="fa-solid fa-plus"></i> Add</button>
        </div>
        <div v-if="showNewIngredientForm" class="mb-3">
            <form method="POST" action="/api/ingredients" enctype="multipart/form-data" class="p-3 bg-neutral-100">
                <input type="hidden" name="_token" :value="csrf_token">
                <label for="name"
                    class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                    <input type="text" required name="name" id="name" placeholder="Name"
                        class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                    <span
                        class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                        Name
                    </span>
                </label>
                <label for="category"
                    class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                    <input list="categoryOptions" type="text" required name="category" id="category" placeholder="Category"
                        class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                    <span
                        class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                        Category
                    </span>
                </label>
                <datalist id="categoryOptions">
                    <option v-for="(item, index) in Object.keys(ingredients)" :key="index" :value="titleCase(item)">
                    </option>
                </datalist>
                <div class="flex items-center justify-center w-full">
                    <label for="image" @drop="drop($event)" @dragover="$event.preventDefault()"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <div v-if="imagePreview[0]" class="py-2 text-center">
                                <img :src="imagePreview[0]" alt="Image Preview" class="h-60">
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
                        <input id="image" required name="image" type="file" class="hidden"
                            @change="imageSelected($event, 0)" />
                    </label>
                </div>
                <div class="py-2 text-center">
                    <button class="bg-emerald-500 py-2 px-6 text-sm font-semibold rounded text-white">Save</button>
                </div>
            </form>
        </div>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="i in 3" :key="i">
                <div v-for="(category, index) in Object.keys(ingredients).slice(Math.ceil((i - 1) * Object.keys(ingredients).length / 3), Math.ceil((i) * Object.keys(ingredients).length / 3))"
                    :key="index">
                    <h3 class="text-center py-1 text-lg">
                        <span class="inline-block py-2 px-4 bg-yellow-200">{{ titleCase(category) }}</span>
                    </h3>
                    <div class="flex flex-wrap justify-center p-2 border bg-neutral-100">
                        <div class="m-0.5 py-1 px-2 text-center rounded transition-all hover:bg-amber-200 cursor-pointer"
                            v-for="item in ingredients[category]" :key="item.id" @click="edit(item)">
                            <img class="w-12 h-12 rounded-full block mx-auto my-1 border object-cover"
                                :src="item.image_url + '?small'" :alt="item.name">
                            <span class="text-sm font-medium py-1 inline-block">{{ titleCase(item.name) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="ingredient_edit"
            :class="'fixed pt-8 top-0 left-0 w-full h-full transition-all animate-pop bg-black bg-opacity-20'">
            <div class="bg-white p-6 border shadow-lg max-w-auto w-[600px] overflow-y-auto flex-0 mx-auto rounded-lg">
                <form method="POST" enctype="multipart/form-data" :action="'/api/ingredients/' + ingredient_edit.id">
                    <input type="hidden" name="_method" value="put" />
                    <input type="hidden" name="_token" :value="csrf_token">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="name"
                                class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                                <input type="text" name="name" id="name" placeholder="Name" :value="ingredient_edit.name"
                                    class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                                <span
                                    class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                                    Name
                                </span>
                            </label>
                            <label for="category"
                                class="relative block bg-white mb-2 overflow-hidden rounded-md border border-gray-200 px-3 pt-3 shadow-sm focus-within:border-blue-600">
                                <input list="categoryOptions1" type="text" name="category" id="category"
                                    placeholder="Category" :value="ingredient_edit.category"
                                    class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent outline-none sm:text-sm" />
                                <span
                                    class="absolute start-3 top-3 -translate-y-1/2 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-3 peer-focus:text-xs">
                                    Category
                                </span>
                            </label>
                            <datalist id="categoryOptions1">
                                <option v-for="(item, index) in Object.keys(ingredients)" :key="index"
                                    :value="titleCase(item)"></option>
                            </datalist>
                            <div>
                                <p class="py-2">
                                    Recipes: {{ ingredient_edit.recipes_count }}
                                </p>
                            </div>
                        </div>
                        <div class="pb-2">
                            <div class="h-0 pt-[100%] relative">
                                <img :src="ingredient_edit.image_url"
                                    class="absolute inset-0 w-full h-full object-cover border p-1" alt="Image">
                            </div>
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
                        <button type="submit"
                            class="py-2 px-4 rounded mr-2 bg-teal-700 hover:bg-teal-600 active:bg-teal-700 text-white">Save</button>
                        <button class="py-2 px-4 rounded bg-slate-700 hover:bg-slate-600 active:bg-slate-700 text-white"
                            @click="ingredient_edit = null; imagePreview[1] = null" type="button">Cancel</button>
                        <form class="inline-block ml-2" @submit="deleteIngredient($event)" v-if="ingredient_edit.recipes_count === 0"
                            :action="'/api/ingredients/' + ingredient_edit.id" method="POST">
                            <input type="hidden" name="_method" value="delete">
                            <input type="hidden" name="_token" :value="csrf_token">
                            <button type="submit" class="inline-block py-2 px-4 bg-red-400 rounded hover:bg-red-300 text-white">Delete</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'Ingredients',
    data() {
        return {
            ingredients: {},
            showNewIngredientForm: false,
            imagePreview: [null, null],
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            ingredient_edit: null,
        }
    },
    mounted() {
        this.fetchIngredients();
    },
    methods: {
        fetchIngredients() {
            fetch("/api/ingredients")
                .then(res => res.json())
                .then(res => {
                    res.forEach(element => { this.ingredients[element.category] = [...(this.ingredients[element.category] ? this.ingredients[element.category] : []), element] });
                })
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
        edit(ingredient) {
            this.ingredient_edit = ingredient
        },
        deleteIngredient(event) {
            const d = confirm("Delete ?");
            d ? d : event.preventDefault()
        }
    }
}
</script>