<template>
    <nav class="flex-grow">
        <ul class="mt-6">
            <li class="mb-2">
                <router-link class="group" to="/">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-solid fa-house fa-lg w-7 mr-2"></i>Dashboard</span>
                </router-link>
            </li>
            <li class="mb-2">
                <router-link class="group" to="/recipes">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-regular fa-rectangle-list fa-lg w-7 mr-2"></i>Recipes</span>
                </router-link>
            </li>
            <li class="mb-2">
                <router-link class="group" to="/articles">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-regular fa-newspaper fa-lg w-7 mr-2"></i>Articles</span>
                </router-link>
            </li>
            <li class="mb-2">
                <router-link class="group" to="/categories">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-solid fa-tag fa-lg w-7 mr-2"></i>Categories</span>
                </router-link>
            </li>
            <li class="mb-2">
                <router-link class="group" to="/ingredients">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-solid fa-basket-shopping w-7 mr-2"></i>Ingredients</span>
                </router-link>
            </li>
            <li class="mb-2">
                <router-link class="group" to="/users">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-solid fa-users fa-lg w-7 mr-2"></i>Users</span>
                </router-link>
            </li>
            <li class="mb-2">
                <router-link class="group" to="/settings">
                    <span class="group-[.active]:text-gray-900 group-[.active]:bg-gray-100 px-4 py-3 text-gray-600 hover:bg-gray-100 font-semibold hover:text-gray-900 block text-sm md:text-base"><i class="fa-solid fa-gear fa-lg w-7 mr-2"></i>Settings</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>

export default {
    name: "Sidebar",
    components: {}
}
</script>