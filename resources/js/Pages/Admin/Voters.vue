<template>
  <DashboardLayout page-title="Voters Management" page-subtitle="View and manage registered voters.">
    <div class="voters-header">
      <div class="voters-header-content">
        <h1>Registered Voters</h1>
        <p>Manage and monitor all registered voters in the system</p>
        <div class="voters-stats">
          <div class="stat-box">
            <div class="stat-box-label">Total Voters</div>
            <div class="stat-box-value">{{ voters.length }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="voters-container">
      <div v-for="voter in voters" :key="voter.id" class="voter-row">
        <div class="voter-info">
          <div class="voter-name">{{ voter.name }}</div>
          <div class="voter-email">📧 {{ voter.email }}</div>
          <div class="voter-course">📚 Course: {{ voter.course || 'Undecided' }}</div>
        </div>
        <div class="voter-actions">
          <button type="button" class="btn btn-danger btn-sm" @click="confirmDestroy(voter.destroy_url)">Delete</button>
        </div>
      </div>
      <div v-if="!voters.length" class="voters-empty">
        <div class="voters-empty-icon">👥</div>
        <div class="voters-empty-title">No Voters Yet</div>
        <div class="voters-empty-text">No voters have been registered in the system yet.</div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { router } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

defineProps({
  voters: { type: Array, default: () => [] },
});

function confirmDestroy(url) {
  if (confirm('Are you sure you want to delete this voter?')) {
    router.delete(url);
  }
}
</script>

<style scoped>
.voters-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; padding: 40px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25); }
.voters-header-content { position: relative; z-index: 1; }
.voters-header h1 { font-size: 32px; font-weight: 700; margin: 0 0 15px 0; }
.voters-header p { font-size: 16px; opacity: 0.95; margin: 0; }
.voters-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
.stat-box { background: rgba(255, 255, 255, 0.15); padding: 15px 20px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.2); }
.stat-box-label { font-size: 12px; opacity: 0.85; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.stat-box-value { font-size: 24px; font-weight: 700; margin-top: 8px; }
.voters-container { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); border: 1px solid #e5e7eb; }
.voter-row { display: flex; align-items: center; justify-content: space-between; padding: 20px; border-bottom: 1px solid #e5e7eb; }
.voter-row:last-child { border-bottom: none; }
.voter-name { font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 8px; }
.voter-email { font-size: 14px; color: #6b7280; margin-bottom: 4px; }
.voter-course { font-size: 13px; color: #9ca3af; font-weight: 500; }
.voters-empty { text-align: center; padding: 60px 40px; }
.voters-empty-icon { font-size: 64px; margin-bottom: 20px; opacity: 0.5; }
.voters-empty-title { font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 10px; }
.voters-empty-text { font-size: 16px; color: #6b7280; margin-bottom: 30px; }
</style>
