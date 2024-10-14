<template>
  <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
              <h1 class="mb-0">Login to Mini CRM</h1>
            </div>
            <div class="card-body">
              <form @submit.prevent="login">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input v-model="email" type="email" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input v-model="password" type="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: ''
        };
    },
    methods: {
        login() {
            axios.post('/login', {
                email: this.email,
                password: this.password
            })
            .then(response => {
                localStorage.setItem('token', response.data.token);  // Store token
                window.location.href = '/home';  // Redirect the user to the home page
            })
            .catch(error => {
                alert('Login Failed')
                console.error('Login failed:', error);
            });
        }
    }
}
</script>
