<template>
    <div>
        <h2>Transactions</h2>

        <!-- Add New Transaction Form -->
        <button class="btn btn-primary mb-3" @click="showTransactionForm">Add New Transaction</button>

        <form v-if="transactionFormVisible" @submit.prevent="saveTransaction">
            <div class="form-group">
                <label for="client">Client</label>
                <select v-model="transaction.client_id" class="form-control" required>
                    <option v-for="client in clients" :key="client.id" :value="client.id">
                        {{ client.first_name }} {{ client.last_name }}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="transactionDate">Transaction Date</label>
                <input v-model="transaction.transaction_date" type="date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input v-model="transaction.amount" type="number" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Save Transaction</button>
        </form>

        <!-- Transactions Table -->
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Client</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="transaction in transactions" :key="transaction.id">
                <td>{{ transaction.client.first_name }} {{ transaction.client.last_name }}</td>
                <td>{{ transaction.transaction_date }}</td>
                <td>{{ transaction.amount }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" @click="editTransaction(transaction)">Edit</button>
                    <button class="btn btn-sm btn-danger" @click="deleteTransaction(transaction.id)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Display current page of transactions from the total -->
        <div class="text-muted mb-5">
            Showing page {{ pagination.current_page }} of {{ pagination.last_page }}
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li v-if="pagination.prev_page_url" class="page-item">
                    <a class="page-link" href="#"
                       @click.prevent="fetchTransactions(pagination.prev_page_url)">Previous</a>
                </li>
                <li v-if="pagination.next_page_url" class="page-item">
                    <a class="page-link" href="#" @click.prevent="fetchTransactions(pagination.next_page_url)">Next</a>
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
            transactions: [],
            clients: [], // To populate the dropdown list for clients
            pagination: {},
            transactionFormVisible: false,
            isEditing: false,
            transaction: {
                id: null,
                client_id: '',
                transaction_date: '',
                amount: ''
            }
        };
    },
    methods: {
        // Fetch all transactions with pagination
        //Setting a default value for the pageUrl in case that we need to overide it when calling
        //the function
        fetchTransactions(pageUrl = '/api/transactions') {
            axios.get(pageUrl, {
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`} // Add authorization header
            })
                .then(response => {
                    this.transactions = response.data.data;
                    this.pagination = response.data;
                })
                .catch(error => {
                    console.error('Error fetching transactions:', error);
                });
        },
        // Fetch all clients for dropdown
        //TODO: If many clients are in the system then lazy loading will be better
        fetchClients() {
            axios.get('/api/clients/all', {
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`}
            })
                .then(response => {
                    this.clients = response.data; // Populate clients for the select dropdown
                })
                .catch(error => {
                    console.error('Error fetching clients:', error);
                });
        },
        showTransactionForm() {
            this.resetForm();
            this.transactionFormVisible = !this.transactionFormVisible;
        },
        saveTransaction() {
            let apiCall;
            const data = {
                client_id: this.transaction.client_id,
                transaction_date: this.transaction.transaction_date,
                amount: this.transaction.amount
            };

            if (this.isEditing) {
                // Update an existing transaction
                apiCall = axios.put(`/api/transactions/${this.transaction.id}`, data, {
                    headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`}
                });
            } else {
                // Create a new transaction
                apiCall = axios.post('/api/transactions', data, {
                    headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`}
                });
            }

            apiCall
                .then(response => {
                    this.fetchTransactions();
                    this.transactionFormVisible = false; // Hide the form after saving
                })
                .catch(error => {
                    console.error('Error saving transaction:', error);
                });
        },
        deleteTransaction(id) {
            axios.delete(`/api/transactions/${id}`, {
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`} // Add authorization header
            })
                .then(response => {
                    this.fetchTransactions();
                })
                .catch(error => {
                    console.error('Error deleting transaction:', error);
                });
        },
        editTransaction(transaction) {
            this.transaction = {...transaction}; // Clone the transaction object for editing
            this.isEditing = true;
            this.transactionFormVisible = true;
        },
        resetForm() {
            this.transaction = {
                id: null,
                client_id: '',
                transaction_date: '',
                amount: ''
            };
            this.isEditing = false;
        }
    },
    mounted() {
        this.fetchTransactions();
        this.fetchClients(); // Fetch clients for the form when the component is mounted
    }
};
</script>
