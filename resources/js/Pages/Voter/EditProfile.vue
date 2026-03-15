<template>
  <DashboardLayout page-title="Edit Your Profile" page-subtitle="Update your voter information.">
    <div class="edit-form-header">
      <h1>Edit Your Profile</h1>
      <p>Update your account information and password</p>
    </div>

    <div class="edit-form-container">
      <div v-if="flash.success" class="success-alert">{{ flash.success }}</div>

      <form @submit.prevent="form.put(urls.voter.profile_update)">
        <div class="form-section">
          <div class="form-section-title">👤 Personal Information</div>
          <div class="form-group">
            <label for="name">Full Name *</label>
            <input id="name" v-model="form.name" type="text" required placeholder="Enter your full name" />
            <div v-if="form.errors.name" class="form-error">{{ form.errors.name }}</div>
          </div>
          <div class="form-group">
            <label for="email">Email Address *</label>
            <input id="email" v-model="form.email" type="email" required placeholder="Enter your email address" />
            <div v-if="form.errors.email" class="form-error">{{ form.errors.email }}</div>
          </div>
          <div class="form-group">
            <label for="course">Course/Program</label>
            <input id="course" v-model="form.course" type="text" placeholder="e.g., Computer Science" />
            <div v-if="form.errors.course" class="form-error">{{ form.errors.course }}</div>
          </div>
        </div>

        <div class="form-section">
          <div class="form-section-title">🔐 Security</div>
          <div class="user-info-box">💡 Leave the password fields blank to keep your current password</div>
          <div class="form-group">
            <label for="password">New Password</label>
            <input id="password" v-model="form.password" type="password" placeholder="Enter a new password (optional)" />
            <div v-if="form.errors.password" class="form-error">{{ form.errors.password }}</div>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" v-model="form.password_confirmation" type="password" placeholder="Confirm your new password" />
          </div>
        </div>

        <div class="form-actions">
          <Link :href="urls.voter.profile" class="btn btn-secondary">Cancel</Link>
          <button type="submit" class="btn btn-primary" :disabled="form.processing">💾 Save Changes</button>
        </div>
      </form>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const props = defineProps({
  voter: { type: Object, required: true },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };
const flash = { success: typeof page.props.flash?.success === 'function' ? page.props.flash.success() : page.props.flash?.success };

const form = useForm({
  name: props.voter.name,
  email: props.voter.email,
  course: props.voter.course ?? '',
  password: '',
  password_confirmation: '',
});
</script>

<style scoped>
.edit-form-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; padding: 40px; border-radius: 16px; margin-bottom: 30px; }
.edit-form-header h1 { font-size: 28px; font-weight: 700; margin: 0 0 10px 0; }
.edit-form-header p { font-size: 15px; opacity: 0.95; margin: 0; }
.edit-form-container { max-width: 600px; margin: 0 auto; }
.form-section { background: white; border-radius: 16px; padding: 30px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; }
.form-section-title { font-size: 16px; font-weight: 700; color: #047857; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #ecfdf3; text-transform: uppercase; letter-spacing: 0.5px; }
.form-group { margin-bottom: 24px; }
.form-group label { display: block; font-weight: 700; margin-bottom: 12px; color: #1f2937; font-size: 14px; }
.form-group input { padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; width: 100%; }
.form-error { color: #dc2626; font-size: 13px; margin-top: 8px; font-weight: 500; }
.user-info-box { background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%); padding: 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; color: #047857; font-weight: 600; }
.form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 25px; border-top: 2px solid #e5e7eb; }
.success-alert { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border: 1px solid rgba(5, 150, 105, 0.3); color: #065f46; padding: 16px; border-radius: 10px; margin-bottom: 25px; font-weight: 600; }
</style>
