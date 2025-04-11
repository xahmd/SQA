<template>
    <div class="bg-neutral-100 p-0 animate-push">
        <form :action="'/api/recipes/' + recipe.id" method="POST" autocomplete="off" enctype="multipart/form-data" v-if="recipe">
            <input type="hidden" name="_token" :value="csrf_token"> 
            <input type="hidden" name="_method" value="PUT">
            <h1 class="text-3xl font-semibold bg-white p-3">Edit Recipe</h1>
            <div class="grid md:grid-cols-3 gap-3 mt-3">
                <div class="col-span-2 p-3 bg-white">
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b">
                                <td class="w-[1%] py-2 px-4 align-top">Image</td>
                                <td class="py-2 px-4">
                                    <div class="grid grid-cols-2">
                                        <div v-if="recipe.image_url && recipe.image_url != ''" class="">
                                            <img :src="recipe.image_url" alt="">
                                        </div>
                                        <div :class="'flex items-center justify-center w-full' + (recipe.image_url && recipe.image_url != '' ? '' : ' col-span-2')">
                                            <label for="image" @drop="drop($event)" @dragover="$event.preventDefault()" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div v-if="imagePreview" class="py-2 text-center">
                                                        <img :src="imagePreview" alt="Image Preview" class="h-60">
                                                    </div>
                                                    <div v-else id="previewImagePlaceholder">
                                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                        </svg>
                                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                        <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF</p>
                                                    </div>
                                                </div>
                                                <input id="image" name="image" type="file" class="hidden" @change="imageSelected($event)" />
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="w-[1%] py-2 px-4">Title</td>
                                <td class="py-2 px-4">
                                    <div class="relative">
                                        <input name="title" type="text" class="block p-2 bg-neutral-300 focus:bg-neutral-100 focus:shadow-[0_0_0_1px_#ddd] w-full h-10 outline-none" :value="recipe.title">
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="w-[1%] py-2 px-4">Description</td>
                                <td class="py-2 px-4">
                                    <div class="relative">
                                        <input name="description" type="text" class="block p-2 bg-neutral-300 focus:bg-neutral-100 focus:shadow-[0_0_0_1px_#ddd] w-full h-10 outline-none" :value="recipe.description">
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="w-[1%] py-2 px-4">Ingredients</td>
                                <td class="py-2 px-4">
                                    <div class="flex flex-wrap">
                                        <input name="ingredients" type="hidden" :value="recipe_ingredients.reduce((r, e) => (r.push(e.id) ? r : []), [])">
                                        <div v-for="(ingredient, index) in recipe_ingredients" :key="index" class="w-20 h-20 p-2 bg-neutral-100 rounded border mr-2 mb-2 flex justify-center items-center text-center">
                                            {{ ingredient.name }}</div>
                                        <button type="button" @click="addIngredient = true" class="w-20 h-20 bg-neutral-200 mr-2 mb-2 flex justify-center items-center cursor-pointer hover:bg-neutral-300"><i class="fa-solid fa-plus fa-2xl"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="w-[1%] py-5 px-4">Cooking Method</td>
                                <td class="py-5 px-4 flex flex-wrap">
                                    <input type="hidden" name="cooking_method" :value="recipe_cookingMethods.join(',')">
                                    <div class="mb-2 mr-2 relative" v-for="(item, index) in cookingMethods" :key="index">
                                        <input :id="'cookingMethod' + index" :value="item" :checked="recipe_cookingMethods.includes(item)" class="peer absolute w-0 h-0 opacity-0 invisible" type="checkbox">
                                        <label @click="recipe_cookingMethods.includes(item) ? recipe_cookingMethods = recipe_cookingMethods.filter(id => id !== item) : recipe_cookingMethods.push(item)" class="py-1 px-3 rounded bg-neutral-200 block cursor-pointer transition-all hover:scale-95 peer-checked:bg-amber-400">{{ item }}</label>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="w-[1%] py-5 px-4 align-top">Content</td>
                                <td class="py-5 px-4">
                                    <div class="relative">
                                        <textarea class="h-0 w-0 absolute overflow-hidden opacity-0 invisible" v-model="content" name="content"></textarea>
                                        <div class="editor"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-[1%] py-5 px-4 align-top">Instructions</td>
                                <td class="py-5 px-4">
                                    <div class="relative">
                                        <div class="" v-for="(instruction, index) in recipe.instructions" :key="index">
                                            <p><b>{{ index + 1 }}:</b> {{ instruction.content }}</p>
                                        </div>
                                        <div class="relative py-1">
                                            <div v-for="(instruction, index) in instructions" :key="index" class="pl-10 my-2 relative">
                                                <span class="absolute top-0 left-0 h-10 w-10 leading-10 text-center">{{ index + 1 }}</span>
                                                <input name="instructions[]" type="text" v-model="instruction.vlaue" class="block p-2 bg-neutral-300 focus:bg-neutral-100 focus:shadow-[0_0_0_1px_#ddd] w-full h-10 outline-none">
                                            </div>
                                            <button class="py-1 px-2 font-medium text-sm rounded bg-blue-300" type="button" @click="instructions.push({ value: '' })">Add Instruction</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-white p-4">
                    <div class="py-2">Difficulty</div>
                    <div class="px-4">
                        <div class="flex flex-wrap py-4">
                            <input :class="['accent-green-500', 'accent-amber-500', 'accent-red-500'][recipe_difficulty]" type="range" min="0" max="2" v-model="recipe_difficulty"><span class="ml-2">{{ ['Easy', 'Medium', 'Hard'][recipe_difficulty] }}</span>
                            <input type="hidden" name="difficulty_level" :value="['easy', 'medium', 'hard'][recipe_difficulty]">
                        </div>
                    </div>
                    <div class="py-2">Cooking Time (in Minutes)</div>
                    <div class="px-4">
                        <input name="cooking_time" class="w-full p-2 border border-neutral-400" type="number" min="0" :value="recipe.cooking_time">
                    </div>
                    <div class="py-2">Serving Size</div>
                    <div class="px-4">
                        <input name="serving_size" class="w-full p-2 border border-neutral-400" type="number" min="0" :value="recipe.serving_size">
                    </div>
                    <div class="py-2">Tags</div>
                    <div class="px-4">
                        <div class="border inline-flex flex-wrap">
                            <input type="hidden" :value="recipe_tags.join(',')" name="tags">
                            <span @click="recipe_tags.splice(index, 1)" class="m-1 text-sm font-medium p-1 rounded bg-neutral-100 border" v-for="(item, index) in recipe_tags" :key="index">{{ titleCase(item) }}<i class="ml-1 fa-light fa-xmark"></i></span>
                            <input type="text" class="m-1 outline-none indent-[0]" @keydown="addTag($event)">
                        </div>
                    </div>
                    <div class="py-2">Category</div>
                    <div class="px-4 flex flex-wrap">
                        <input type="hidden" name="categories" :value="recipe_categories.join(',')">
                        <div class="mb-2 mr-2 relative" v-for="(item, index) in categories" :key="index">
                            <input :id="'category' + index" :value="item.id" :checked="recipe_categories.includes(item.id)" class="peer absolute w-0 h-0 opacity-0 invisible" type="checkbox">
                            <label @click="recipe_categories.includes(item.id) ? recipe_categories = recipe_categories.filter(id => id !== item.id) : recipe_categories.push(item.id)" :style="`--bg-image: url('/${item.image_url}');`" class="group select-none transition-[transform] hover:scale-95 cursor-pointer border-2 border-amber-400 peer-checked:border-green-400 overflow-hidden pl-20 pt-10 bg-cover rounded flex bg-[image:var(--bg-image)]">
                                <span class="bg-amber-300 group-peer-checked:bg-green-400 p-1 text-sm font-semibold">{{ item.label }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-2 text-center">
                <button class="py-2 px-4 rounded bg-green-500 hover:bg-green-400">Save</button>
            </div>
        </form>
    </div>
    <div v-if="addIngredient" :class="'fixed pt-8 pb-4 top-0 left-0 w-full h-full transition-all animate-pop bg-black bg-opacity-20'">
        <div class="bg-white p-6 border shadow-lg max-w-auto w-[600px] flex flex-col mx-auto rounded-lg max-h-full">
            <div class="pb-5">
                <h2 class="text-center pb-2 text-3xl font-semibold">Add Ingredients</h2>
                <input type="text" placeholder="Search ..." v-model="search_query" class="w-full bg-neutral-100 border h-10 p-2 outline-none rounded">
            </div>
            <div class="overflow-y-auto">
                <div v-for="(elements, category) in Object.keys(ingredients).reduce((r, k) => { r[k] = ingredients[k].filter(i => (i.name.toLowerCase().includes(search_query))); return r }, {})" :key="category" class="border-b py-3">
                    <h3 class="py-2 text-xl font-semibold">{{ titleCase(category) }}</h3>
                    <div class="flex flex-wrap">
                        <div v-for="(ingredient, index) in elements" :key="index" :class="'w-20 h-20 p-2 rounded border mr-2 mb-2 flex justify-center text-center items-center cursor-pointer ' + (isSelected(ingredient) ? 'bg-emerald-100' : 'bg-neutral-200')" @click="select(ingredient)">{{ titleCase(ingredient.name) }}</div>
                    </div>
                </div>
            </div>
            <div class="pt-5 text-center">
                <button type="button" @click="search_query = ''; addIngredient = false" class="py-2 px-5 bg-sky-600 rounded text-white hover:bg-sky-700">Done</button>
            </div>
        </div>
    </div>
</template>
<style>
.ck.ck-toolbar.ck-toolbar_grouping>.ck-toolbar__items {
    flex-wrap: wrap !important;
}
</style>
<script>
const watchdog = new window.CKSource.EditorWatchdog();
window.watchdog = watchdog;
watchdog.setCreator((element, config) => {
    return CKSource.Editor
        .create(element, config)
        .then(editor => {
            return editor;
        });
});
watchdog.setDestructor(editor => {
    return editor.destroy();
});
watchdog.on('error', (e) => { });
import SimpleUploadAdapterPlugin from "../ckeditor-upload-adapter"
export default {
    name: "EditRecipe",
    data() {
        return {
            recipe: null,
            imagePreview: null,
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            recipe_ingredients: [],
            addIngredient: false,
            ingredients: {},
            recipe_categories: [],
            recipe_cookingMethods: [],
            search_query: "",
            recipe_difficulty: 1,
            cookingMethods: ['Bake', 'Boil', 'Braise', 'Broil', 'Fry', 'Grill', 'Roast', 'Poach', 'Sauté', 'Steam', 'Stir-Fry', 'Simmer'],
            categories: [],
            recipe_tags: [],
            content: '',
            instructions: [],
        }
    },
    methods: {
        fetchRecipe() {
            fetch("/api/recipes/" + this.$route.params.id).then(r => r.json()).then(r => this.recipe = r)
                .then(() => this.recipe_ingredients = this.recipe.ingredients)
                .then(() => this.recipe_cookingMethods = this.recipe.cooking_method.split(','))
                .then(() => this.recipe_tags = this.recipe.tags)
                .then(() => this.recipe_categories = this.recipe.categories)
                .then(() => this.recipe_difficulty = ['easy', 'medium', 'hard'].indexOf(this.recipe.difficulty_level.toLowerCase()))
                .finally(() => {this.initEditor()})
        },
        fetchIngredients() {
            fetch("/api/ingredients")
                .then(res => res.json())
                .then(res => {
                    res.forEach(element => { this.ingredients[element.category] = [...(this.ingredients[element.category] ? this.ingredients[element.category] : []), element] });
                    return res;
                })
        },
        fetchCategories() {
            fetch("/api/recipes_categories")
                .then(res => res.json())
                .then(res => (this.categories = res));
        },
        isSelected(ingredient) {
            return this.recipe_ingredients.includes(ingredient)
        },
        select(ingredient) {
            this.isSelected(ingredient) ? (this.recipe_ingredients = this.recipe_ingredients.filter(i => i !== ingredient)) : (this.recipe_ingredients = [...this.recipe_ingredients, ingredient])
        },
        addTag(event) {
            if (event.key === 'Enter') {
                event.preventDefault()
                const tag = event.target.value.toLowerCase().trim()
                if (tag !== "" && !this.recipe_tags.includes(tag)) {
                    this.recipe_tags.push(tag)
                }
                event.target.value = ""
            }
        },
        imageSelected(event) {
            const ref = this
            const input = event.target;
            if (input.files.length > 0) {
                const file = input.files[0]
                const reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onload = function () {
                    ref.imagePreview = reader.result
                };
                reader.onerror = function (error) {
                    console.log('Error: ', error);
                };
            }
            else {
                this.imagePreview = null
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
        initEditor() {
            watchdog.create(document.querySelector('.editor'), {
                initialData: this.recipe.content,
                extraPlugins: [SimpleUploadAdapterPlugin],
            })
                .then(() => {
                    watchdog.editor.model.document.on('change:data', () => {
                        this.content = watchdog.editor.getData();
                    });
                })
                .catch((e) => { });
        }
    },
    mounted() {
        this.fetchRecipe()
        this.fetchIngredients()
        this.fetchCategories()
    },
}
</script>