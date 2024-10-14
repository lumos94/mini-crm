<template>
    <div>
        <h2>Clients</h2>
        <button class="btn btn-primary mb-3" @click="showClientForm">Add New Client</button>

        <form v-if="clientFormVisible" @submit.prevent="saveClient">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input v-model="client.first_name" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input v-model="client.last_name" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input v-model="client.email" type="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input @change="handleFileUpload" type="file" class="form-control" :required="!isEditing">
            </div>
            <button type="submit" class="btn btn-success">Save Client</button>
        </form>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="client in clients" :key="client.id">
                <td>
                    <img :src="client.avatar_url" alt="Avatar" class="img-thumbnail" width="50" height="50"/>
                </td>
                <td>{{ client.first_name }}</td>
                <td>{{ client.last_name }}</td>
                <td>{{ client.email }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" @click="editClient(client)">Edit</button>
                    <button class="btn btn-sm btn-danger" @click="deleteClient(client.id)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Display current page of clients from the total -->
        <div class="text-muted mb-5">
            Showing page {{ pagination.current_page }} of {{ pagination.last_page }}
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li v-if="pagination.prev_page_url" class="page-item">
                    <a class="page-link" href="#" @click.prevent="fetchClients(pagination.prev_page_url)">Previous</a>
                </li>
                <li v-if="pagination.next_page_url" class="page-item">
                    <a class="page-link" href="#" @click.prevent="fetchClients(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            clients: [],
            pagination: {},
            clientFormVisible: false,
            isEditing: false,
            client: {
                id: null,
                first_name: '',
                last_name: '',
                email: '',
                avatar: null,
            }
        };
    },
    methods: {
        // Fetch clients with pagination
        fetchClients(pageUrl = '/api/clients') {
            axios.get(pageUrl, {
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`} // Add authorization header
            })
                .then(response => {
                    this.clients = response.data.data;
                    this.pagination = response.data;
                })
                .catch(error => {
                    console.error('Error fetching clients:', error);
                });
        },
        showClientForm() {
            this.resetForm();
            this.clientFormVisible = !this.clientFormVisible;
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.client.avatar = file;
        },
        // Save a new client or update an existing one
        saveClient() {
            let formData = new FormData();
            formData.append('first_name', this.client.first_name);
            formData.append('last_name', this.client.last_name);
            formData.append('email', this.client.email);

            // Only append the avatar if it's a file (not a string)
            if (this.client.avatar && typeof this.client.avatar !== 'string') {
                formData.append('avatar', this.client.avatar);
            }

            if (this.isEditing) {
                // Update client
                axios.put(`/api/clients/${this.client.id}`, formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                })
                    .then(response => {
                        this.fetchClients();
                        this.resetForm();
                        this.clientFormVisible = false;
                    })
                    .catch(error => {
                        console.error('Error updating client:', error);
                    });
            } else {
                // Create a new client
                axios.post('/api/clients', formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        this.fetchClients();
                        this.clientFormVisible = false;
                    })
                    .catch(error => {
                        console.error('Error creating client:', error);
                    });
            }
        },
        deleteClient(id) {
            console.log(id)
            axios.delete(`/api/clients/${id}`, {
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`} // Add authorization header
            })
                .then(response => {
                    console.log(response)
                    this.fetchClients();
                })
                .catch(error => {
                    console.error('Error deleting client:', error);
                });
        },
        editClient(client) {
            this.client = {...client}; // Clone the client object for editing
            this.isEditing = true;
            this.clientFormVisible = true; // Show the form for editing
        },
        resetForm() {
            this.client = {
                id: null,
                first_name: '',
                last_name: '',
                email: '',
                avatar: null,
            };
            this.isEditing = false;
        }
    },
    mounted() {
        this.fetchClients();
    }
};
</script>
