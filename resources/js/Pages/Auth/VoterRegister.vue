<template>
  <AuthLayout>
    <div class="auth-right">
      <div class="login-card">
        <h1 class="login-title">Student Registration</h1>

        <div v-if="form.errors && Object.keys(form.errors).length" class="error-message">
          <ul style="margin: 0; padding-left: 20px">
            <li v-for="(err, key) in form.errors" :key="key">{{ err }}</li>
          </ul>
        </div>

        <form @submit.prevent="form.post(urls.voter?.register_submit || '/voter/register')">
          <div class="form-group">
            <input v-model="form.name" type="text" class="form-input" placeholder="Full Name" required autofocus />
          </div>
          <div class="form-group">
            <input v-model="form.email" type="email" class="form-input" placeholder="Student Email" required />
          </div>
          <div class="form-group">
            <input v-model="form.course" type="text" class="form-input" placeholder="Course (e.g., BSIT)" required />
          </div>
          <div class="form-group">
            <input v-model="form.password" type="password" class="form-input" placeholder="Password" required />
          </div>
          <div class="form-group">
            <input v-model="form.password_confirmation" type="password" class="form-input" placeholder="Confirm Password" required />
          </div>
          <button type="submit" class="btn-login" :disabled="form.processing">
            {{ form.processing ? 'Registering...' : 'Register' }}
          </button>
        </form>

        <div class="login-footer">
          <Link :href="urls.voter?.login || '/voter/login'" class="login-link">Already have an account? Login</Link>
        </div>
      </div>
    </div>
  </AuthLayout>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import AuthLayout from '../../Layouts/AuthLayout.vue';

const page = usePage();
const urls = page.props.urls?.voter ? page.props.urls : { voter: {} };

const form = useForm({
  name: '',
  email: '',
  course: '',
  password: '',
  password_confirmation: '',
});
</script>
