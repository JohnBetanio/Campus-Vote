<template>
  <DashboardLayout page-title="Vote Confirmation" page-subtitle="Your ballot has been securely submitted.">
    <div class="confirmation-container">
      <div class="confirmation-card">
        <div class="success-icon-wrap" aria-hidden="true">
          <CircleCheck :size="44" class="success-icon-svg" stroke-width="2" />
        </div>
        <h1 class="confirmation-title">Your Vote Has Been Recorded</h1>
        <p class="confirmation-message">
          Thank you for participating in the election. Your votes have been securely recorded and cannot be changed.
        </p>
        <div class="election-info">
          <div class="election-info-label">Election</div>
          <div class="election-info-value">{{ election.title }}</div>
        </div>
        <div class="info-box">
          <div class="info-box-title">
            <Clock :size="18" class="info-box-title-icon" />
            Results timeline
          </div>
          <div class="info-box-text">
            Results will be available after the election period ends. This ensures the integrity and fairness of the
            election by preventing voters from being influenced by partial results.
          </div>
        </div>
        <div class="security-note">
          <Lock :size="18" class="security-note-icon" aria-hidden="true" />
          <span>
            Your vote is anonymous and securely stored. Only election administrators can view aggregated results after
            the election ends.
          </span>
        </div>
        <div class="button-group" style="margin-top: 30px">
          <Link :href="voterUrl('votes', '/voter/votes')" class="btn btn-primary">View Vote History</Link>
          <Link :href="voterUrl('dashboard', '/voter/dashboard')" class="btn btn-secondary">Return to Dashboard</Link>
        </div>
        <p class="countdown-message">
          <Hourglass :size="16" class="countdown-icon" aria-hidden="true" />
          You can return to check results once the election ends.
        </p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import { CircleCheck, Clock, Lock, Hourglass } from 'lucide-vue-next';

defineProps({
  election: { type: Object, required: true },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };

function voterUrl(key, fallback) {
  const u = urls.voter?.[key];
  return u && u !== '#' ? u : fallback;
}
</script>

<style scoped>
.confirmation-container { max-width: 760px; margin: 0 auto; padding: 24px; }
.confirmation-card {
  background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(247,250,252,0.98));
  border-radius: 22px;
  padding: 44px;
  box-shadow: 0 18px 42px rgba(2, 132, 199, 0.14);
  border: 1px solid rgba(14, 116, 144, 0.15);
  text-align: center;
}
.success-icon-wrap {
  width: 90px;
  height: 90px;
  border-radius: 999px;
  margin: 0 auto 18px;
  background: linear-gradient(135deg, #ecfdf5, #d1fae5);
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #86efac;
}
.success-icon-svg {
  color: #059669;
}
.confirmation-title { font-size: 30px; font-weight: 800; color: #0f766e; margin-bottom: 15px; letter-spacing: -0.02em; }
.confirmation-message { font-size: 16px; color: #475569; line-height: 1.65; margin-bottom: 30px; }
.election-info { background: #f0f9ff; border-left: 4px solid #0ea5e9; padding: 16px; border-radius: 12px; margin-bottom: 26px; text-align: left; }
.election-info-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px; }
.election-info-value { font-size: 18px; font-weight: 700; color: #1a7a8f; }
.info-box { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 12px; padding: 16px; margin-bottom: 26px; font-size: 14px; color: #92400e; text-align: left; }
.info-box-title {
  font-weight: 700;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.info-box-title-icon { flex-shrink: 0; color: #b45309; }
.info-box-text { line-height: 1.5; }
.button-group { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
.security-note {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: 10px;
  padding: 14px 14px 14px 16px;
  margin-top: 20px;
  font-size: 13px;
  color: #166534;
  text-align: left;
  display: flex;
  align-items: flex-start;
  gap: 10px;
}
.security-note-icon { flex-shrink: 0; color: #15803d; margin-top: 1px; }
.countdown-message {
  font-size: 14px;
  color: #64748b;
  margin-top: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}
.countdown-icon { flex-shrink: 0; color: #64748b; }
</style>
