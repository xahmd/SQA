import {createApp} from 'vue'
import { createRouter, createWebHashHistory } from "vue-router"
import CKEditor from '@ckeditor/ckeditor5-vue';

import App from './components/Admin.vue'

import Articles from './components/Articles.vue';
import AddArticle from './components/AddArticle.vue';
import Categories from './components/Categories.vue';
import Dashboard from './components/Dashboard.vue';
import Recipes from './components/Recipes.vue';
import Ingredients from './components/Ingredients.vue';
import AddRecipe from './components/AddRecipe.vue';
import Users from './components/Users.vue';
import RecipesCategories from './components/categories/RecipesCategories.vue';
import ArticlesCategories from './components/categories/ArticlesCategories.vue';
import EditRecipe from './components/EditRecipe.vue';
import Settings from './components/Settings.vue';
import EditArticle from './components/EditArticle.vue';


const router = createRouter({
    history: createWebHashHistory(),
    linkActiveClass: "active",
    routes: [
        {
            path: '/categories',
            alias: '/categories/*',
            component: Categories,
            children: [
                {
                  // UserProfile will be rendered inside User's <router-view>
                  // when /user/:id/profile is matched
                  path: 'recipes',
                  component: RecipesCategories,
                },
                {
                  // UserPosts will be rendered inside User's <router-view>
                  // when /user/:id/posts is matched
                  path: 'articles',
                  component: ArticlesCategories,
                },
              ],
        },
        {
            path: '/users',
            alias: '/users/*',
            component: Users
        },
        {
            path: '/ingredients',
            alias: '/ingredients/*',
            component: Ingredients
        },
        {
            path: '/recipes',
            alias: '/recipes/*',
            component: Recipes
        },
        {
            path: '/recipes/create',
            alias: '/recipes/create/*',
            component: AddRecipe
        },
        {
            path: '/recipes/edit/:id',
            component: EditRecipe
        },
        {
            path: '/articles',
            alias: '/articles/*',
            component: Articles
        },
        {
            path: '/articles/create',
            alias: '/articles/create/*',
            component: AddArticle
        },
        {
            path: '/articles/edit/:id',
            component: EditArticle
        },
        {
            path: '/settings',
            alias: '/settings/*',
            component: Settings
        },
        {
            path: '/',
            alias: '/*',
            component: Dashboard
        }
    ]
})

const app = createApp(App);
app.config.globalProperties.titleCase = (s) => (s.toLowerCase().split(' ').map((w)=>(w.replace(w[0],w[0].toUpperCase()))).join(' '))
app.use(router)
app.use(CKEditor)
app.mount("#app")