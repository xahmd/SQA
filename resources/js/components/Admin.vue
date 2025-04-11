<template>
    <div class="flex flex-col flex-shrink-0 overflow-y-auto w-48 md:w-56 lg:w-64 bg-white">
        <Sidebar />
    </div>
    <div class="flex flex-col flex-grow-1 w-full overflow-y-auto bg-gray-100 p-5">
        <router-view></router-view>
    </div>
</template>

<script>
import Sidebar from './Sidebar.vue';

export default {
    name: "Admin",
    components: { Sidebar },
}
</script>