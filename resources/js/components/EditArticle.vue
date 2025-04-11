<template>
    <div class="bg-neutral-100 p-0 animate-push">
        <form :action="'/api/articles/' + article.id" method="POST" autocomplete="off" enctype="multipart/form-data" v-if="article">
            <input type="hidden" name="_token" :value="csrf_token">
            <h1 class="text-3xl font-semibold bg-white p-3">Create New article</h1>
            <p class="text-sm text-neutral-700 bg-white px-3 pb-3">Craft a Fresh, Innovative article.</p>
            <div class="grid md:grid-cols-3 gap-3 mt-3">
                <div class="col-span-2 p-3 bg-white">
                    <table class="w-full">
                        <tbody>
                            <tr class="border-b">
                                <td class="w-[1%] py-2 px-4 align-top">Image</td>
                                <td class="py-2 px-4">
                                    <div class="grid grid-cols-2">
                                        <div v-if="article.image_url && article.image_url != ''" class="">
                                            <img :src="article.image_url" alt="">
                                        </div>
                                        <div :class="'flex items-center justify-center w-full' + (article.image_url && article.image_url != '' ? '' : ' col-span-2')">
                                            <label for="image" @drop="drop($event)" @dragover="$event.preventDefault()" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div v-if="imagePreview" class="py-2 text-center">
                                                        <img :src="imagePreview" alt="Image Preview" class="h-60">
                                                    </div>
                                                    <div v-else id="previewImagePlaceholder">
                                                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                        </svg>
                                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click
                                                                to
                                                                upload</span> or drag and drop</p>
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
                                        <input name="title" type="text" class="block p-2 bg-neutral-300 focus:bg-neutral-100 focus:shadow-[0_0_0_1px_#ddd] w-full h-10 outline-none" :value="article.title">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-[1%] py-5 px-4 align-top">Content</td>
                                <td class="py-5 px-4">
                                    <div class="relative">
                                        <textarea class="h-0 w-0 absolute overflow-hidden opacity-0 invisible" v-model="content" name="content"></textarea>
                                        <div class="editor"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="bg-white p-4">
                    <div class="py-2">Tags</div>
                    <div class="px-4">
                        <div class="border inline-flex flex-wrap">
                            <input type="hidden" :value="article_tags.join(',')" name="tags">
                            <span @click="article_tags.splice(index, 1)" class="m-1 text-sm font-medium p-1 rounded bg-neutral-100 border" v-for="(item, index) in article_tags" :key="index">{{ titleCase(item) }}<i class="ml-1 fa-light fa-xmark"></i></span>
                            <input type="text" class="m-1 outline-none indent-[0]" @keydown="addTag($event)">
                        </div>
                    </div>
                    <div class="py-2">Category</div>
                    <div class="px-4 flex flex-wrap">
                        <input type="hidden" name="categories" :value="article_categories.join(',')">
                        <div class="mb-2 mr-2 relative" v-for="(item, index) in categories" :key="index">
                            <input :id="'category' + index" :value="item.id" :checked="article_categories.includes(item.id)" class="peer absolute w-0 h-0 opacity-0 invisible" type="checkbox">
                            <label @click="article_categories.includes(item.id) ? article_categories = article_categories.filter(id => id !== item.id) : article_categories.push(item.id)" :style="`--bg-image: url('/${item.image_url}');`" class="group select-none transition-[transform] hover:scale-95 cursor-pointer border-2 border-amber-400 peer-checked:border-green-400 overflow-hidden pl-20 pt-10 bg-cover rounded flex bg-[image:var(--bg-image)]">
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
watchdog.on('error', (error) => { });
import SimpleUploadAdapterPlugin from "../ckeditor-upload-adapter"
export default {
    name: "EditArticle",
    data() {
        return {
            imagePreview: null,
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            article_categories: [],
            categories: [],
            article_tags: [],
            content: '',
            article: null,
        }
    },
    methods: {
        fetchArticle() {
            fetch("/api/articles/" + this.$route.params.id).then(r => r.json()).then(r => this.article = r)
                .then(() => this.article_tags = this.article.tags)
                .then(() => this.article_categories = this.article.categories)
                .finally(() => { this.initEditor() })
        },
        fetchCategories() {
            fetch("/api/articles_categories")
                .then(res => res.json())
                .then(res => (this.categories = res));
        },
        addTag(event) {
            if (event.key === 'Enter') {
                event.preventDefault()
                const tag = event.target.value.toLowerCase().trim()
                if (tag !== "" && !this.article_tags.includes(tag)) {
                    this.article_tags.push(tag)
                }
                event.target.value = ""
            }
        },
        onReady(editor) {
            editor.ui.getEditableElement().parentElement.insertBefore(
                editor.ui.view.toolbar.element,
                editor.ui.getEditableElement()
            );
        },
        update() {
            if (this.editor.data[this.editor.data.length - 1] == "\n") this.editor.data += " "
        },
        sync_scroll(element) {
            let result_element = document.querySelector("#highlighting");
            result_element.scrollTop = element.scrollTop;
            result_element.scrollLeft = element.scrollLeft;
        },
        check_tab(element, event) {
            let code = this.editor.data;
            if (event.key == "Tab") {
                event.preventDefault();
                let before_tab = code.slice(0, element.selectionStart);
                let after_tab = code.slice(element.selectionEnd, this.editor.data.length);
                let cursor_pos = element.selectionStart + 1;
                this.editor.data = before_tab + "\t" + after_tab;
                element.selectionStart = cursor_pos;
                element.selectionEnd = cursor_pos;
                update();
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
                initialData: this.article.content,
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
        this.fetchArticle()
        this.fetchCategories()
    },
}
</script>