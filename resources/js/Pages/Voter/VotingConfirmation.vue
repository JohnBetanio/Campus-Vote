<template>
  <DashboardLayout page-title="Vote Confirmation" page-subtitle="">
    <div class="confirmation-container">
      <div class="confirmation-card">
        <div class="success-icon">✓</div>
        <h1 class="confirmation-title">Your Vote Has Been Recorded</h1>
        <p class="confirmation-message">
          Thank you for participating in the election. Your votes have been securely recorded and cannot be changed.
        </p>
        <div class="election-info">
          <div class="election-info-label">Election</div>
          <div class="election-info-value">{{ election.title }}</div>
        </div>
        <div class="info-box">
          <div class="info-box-title">⏱️ Results Timeline</div>
          <div class="info-box-text">
            Results will be available after the election period ends. This ensures the integrity and fairness of the
            election by preventing voters from being influenced by partial results.
          </div>
        </div>
        <div class="security-note">
          <span class="security-note-icon">🔐</span>
          Your vote is anonymous and securely stored. Only election administrators can view aggregated results after
          the election ends.
        </div>
        <div class="button-group" style="margin-top: 30px">
          <Link :href="urls.voter.votes" class="btn btn-primary">📋 View Vote History</Link>
          <Link :href="urls.voter.dashboard" class="btn btn-secondary">🏠 Return to Dashboard</Link>
        </div>
        <p class="countdown-message">⏳ You can return to check results once the election ends.</p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

defineProps({
  election: { type: Object, required: true },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };
</script>

<style scoped>
.confirmation-container { max-width: 600px; margin: 0 auto; padding: 20px; }
.confirmation-card { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(5, 150, 105, 0.15); text-align: center; }
.success-icon { font-size: 80px; margin-bottom: 20px; color: #059669; }
.confirmation-title { font-size: 28px; font-weight: 700; color: #1a7a8f; margin-bottom: 15px; }
.confirmation-message { font-size: 16px; color: #555; line-height: 1.6; margin-bottom: 30px; }
.election-info { background: #f0f9ff; border-left: 4px solid #059669; padding: 15px; border-radius: 8px; margin-bottom: 30px; text-align: left; }
.election-info-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px; }
.election-info-value { font-size: 18px; font-weight: 700; color: #1a7a8f; }
.info-box { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; padding: 15px; margin-bottom: 30px; font-size: 14px; color: #92400e; }
.info-box-title { font-weight: 700; margin-bottom: 8px; }
.info-box-text { line-height: 1.5; }
.button-group { display: flex; gap: 12px; flex-direction: column; align-items: center; }
.security-note { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 13px; color: #166534; }
.countdown-message { font-size: 14px; color: #666; margin-top: 20px; }
</style>
