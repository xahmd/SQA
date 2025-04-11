<template>
    <div class="bg-white p-6 animate-push">
        <h2 class="mb-4 text-2xl font-semibold">Settings</h2>
        <div v-if="message" class="text-center py-2">{{ message }}</div>
        <form method="post" @submit.prevent novalidate autocomplete="off" v-if="admin">
            <div class="mb-5">
                <h3 class="mb-2 text-xl font-semibold">Change Info</h3>
                <div class="mb-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" v-model="email" placeholder="Email" required class="mt-1 block rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" v-model="name" placeholder="Name" required class="mt-1 block rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button @click="save" type="submit" class="bg-green-500 rounded-md text-white hover:bg-green-400 transition-all px-3 py-2 block" v-if="name != admin.name || email != admin.email">Save</button>
            </div>
            <div>
                <h3 class="mb-2 text-xl font-semibold">Change Password</h3>
                <input type="password" v-model="old_password" name="old_password" id="old_password" placeholder="Old Password" required class="my-2 block rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="password" v-model="new_password" name="new_password" id="new_password" placeholder="New Password" required class="my-2 block rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="password" v-model="new_password_confirmation" name="new_password_confirmation" id="new_password_confirmation" placeholder="New Password Confirmation" required class="my-2 block rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button @click="save_password" type="submit" class="bg-green-500 rounded-md text-white hover:bg-green-400 transition-all px-3 py-2 block" v-if="old_password != '' && new_password.length > 5 && new_password == new_password_confirmation">Save</button>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    name: "Settings",
    data() {
        return {
            csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            admin: null,
            name: '',
            email: '',
            old_password: '',
            new_password: '',
            new_password_confirmation: '',
            message: null
        }
    },
    methods: {
        fetchAdmin() {
            fetch("/api/admin").then(e => e.json()).then(a => { this.admin = a; this.name = a.name; this.email = a.email })
        },
        save() {
            fetch("/settings", {
                headers: {
                    "Content-Type": "application/json"
                },
                method: 'POST',
                body: JSON.stringify({
                    _token: this.csrf_token,
                    name: this.name,
                    email: this.email
                })
            }).then(a => a.json()).then(a => {
                if (a.success) {
                    this.message = "Saved Successfully";
                    this.admin = a.admin;
                    this.name = a.admin.name;
                    this.email = a.admin.email;
                } else {
                    this.message = a.error;
                }
            })
        },
        save_password() {
            fetch("/settings", {
                headers: {
                    "Content-Type": "application/json"
                },
                method: 'POST',
                body: JSON.stringify({
                    _token: this.csrf_token,
                    old_password: this.old_password,
                    new_password: this.new_password,
                    new_password_confirmation: this.new_password_confirmation
                })
            }).then(a => a.json()).then(a => {
                if (a.success) {
                    this.message = "Saved Successfully";
                    this.old_password = this.new_password = this.new_password_confirmation = '';
                } else {
                    this.message = a.error;
                }
            })
        }
    },
    mounted() {
        this.fetchAdmin();
    }
}
</script>