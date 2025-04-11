<template>
    <div class="bg-white p-6 animate-push">
    <!-- Users -->
    <h2 class="mb-4 text-2xl font-semibold">All Users</h2>
    <table class="w-full rounded shadow border text-sm">
        <thead>
            <tr class="text-left bg-gray-100 uppercase border-b">
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Saved Recipes</th>
                <th class="px-4 py-3">Saved Articles</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <tr class="border-b">
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="relative w-8 h-8 mr-3 rounded-full">
                            <img class="object-cover w-full h-full rounded-full" src="https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=200" />
                        </div>
                        <div>
                            <p class="font-semibold">Nasser</p>
                            <p class="text-xs text-gray-600">Pen Tester</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">29</td>
                <td class="px-4 py-3">100</td>
                <td class="px-4 py-3">6/10/2020</td>
            </tr>
            <tr class="border-b" v-for="(user, index) in users" :key="index">
                <td class="px-4 py-3">
                    <div class="flex items-center">
                        <div class="relative w-8 h-8 mr-3 rounded-full">
                            <img class="object-cover w-full h-full rounded-full" :src="user.photo_url ? user.photo_url : 'https://images.pexels.com/photos/771742/pexels-photo-771742.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=200'" />
                        </div>
                        <div>
                            <p class="font-semibold">{{ user.firstname }}</p>
                            <p class="text-xs text-gray-600">{{ user.description }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">{{ user.recipes_count }}</td>
                <td class="px-4 py-3">{{ user.articles_count }}</td>
                <td class="px-4 py-3">{{ `${(new Date((user.created_at))).getDate()}/${(new Date((user.created_at))).getMonth() + 1}/${(new Date((user.created_at))).getFullYear()}` }}</td>
            </tr>
        </tbody>
    </table>
</div>
</template>
<script>
export default {
    name: "Users",
    data() {
        return {
            users: []
        }
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        fetchUsers() {
            fetch("/api/users")
                .then(res => res.json())
                .then(data => { this.users = data })
        }
    }
}
</script>