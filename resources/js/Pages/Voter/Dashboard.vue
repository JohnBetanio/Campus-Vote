<template>
  <DashboardLayout
    page-title="Welcome"
    page-subtitle="Cast your vote and track election results."
  >
    <template #actions>
      <Link :href="urls.voter.vote" class="btn btn-primary">🗳️ Vote Now</Link>
      <Link :href="urls.voter.results" class="btn btn-secondary">View Results</Link>
    </template>

    <div class="dashboard-content" style="max-width: 1280px; margin: 0 auto">
      <!-- Welcome Banner -->
      <section class="welcome-banner" aria-labelledby="welcome-heading">
        <h1 id="welcome-heading" class="welcome-title">Welcome, {{ voter?.name || 'Voter' }}</h1>
        <p class="welcome-subtitle">Your secure voting dashboard for all campus elections.</p>
      </section>

      <!-- Quick action cards -->
      <section class="kpi-grid kpi-grid--actions" aria-label="Quick actions">
        <Link
          :href="urls.voter.votes"
          class="kpi-card kpi-card--action"
          title="View your vote history"
        >
          <div class="kpi-card__icon" aria-hidden="true">VH</div>
          <div class="kpi-card__value">Vote History</div>
          <div class="kpi-card__detail">Review all your submitted votes across past campus elections</div>
          <span class="kpi-card__cta">Open history</span>
        </Link>
        <Link
          :href="urls.voter.results"
          class="kpi-card kpi-card--action"
          title="View election results"
        >
          <div class="kpi-card__icon" aria-hidden="true">ER</div>
          <div class="kpi-card__value">Election Results</div>
          <div class="kpi-card__detail">See current and past results with live updates when voting is active</div>
          <span class="kpi-card__cta">View results</span>
        </Link>
        <Link
          :href="urls.voter.profile"
          class="kpi-card kpi-card--action"
          title="Manage your profile"
        >
          <div class="kpi-card__icon" aria-hidden="true">PR</div>
          <div class="kpi-card__value">My Profile</div>
          <div class="kpi-card__detail">Keep your voter information up to date and secure</div>
          <span class="kpi-card__cta">Manage profile</span>
        </Link>
      </section>

      <!-- Recent Winners Announcement -->
      <div v-if="recentWinners && recentWinners.length > 0" class="card recent-winners-card">
        <h3 class="recent-winners-title">Recent Election Winners</h3>
        <ul class="recent-winners-list">
          <li v-for="rw in recentWinners" :key="rw.id">
            <template v-if="rw.winners && Object.keys(rw.winners).length">
              <strong>{{ rw.title }}:</strong>
              <template v-for="(info, pos) in rw.winners" :key="pos">
                {{ pos }} – {{ info.name }} ({{ info.votes }})<span v-if="Object.keys(rw.winners).indexOf(pos) < Object.keys(rw.winners).length - 1">, </span>
              </template>
            </template>
          </li>
        </ul>
      </div>

      <!-- Active Elections -->
      <section aria-labelledby="active-elections-heading">
        <h2 id="active-elections-heading" class="section-header">Active elections</h2>
        <div v-if="activeElections.length > 0" class="elections-grid">
          <div
            v-for="election in activeElections"
            :key="election.id"
            class="election-card"
          >
            <div class="election-card-header">
              <h3 class="election-card-title">{{ election.title }}</h3>
              <p class="election-card-date">📅 Created {{ formatDate(election.created_at) }}</p>
            </div>

            <div class="election-card-body">
              <div class="election-stat">
                <span class="election-stat-label">📌 Positions:</span>
                <span class="election-stat-value">{{ election.positions_count }}</span>
              </div>
              <div class="election-stat">
                <span class="election-stat-label">👥 Candidates:</span>
                <span class="election-stat-value">{{ election.candidates_count }}</span>
              </div>
              <div class="election-stat">
                <span class="election-stat-label">🗳️ Votes Cast:</span>
                <span class="election-stat-value" style="color: #999">Hidden</span>
              </div>

              <span v-if="!election.has_voted" class="election-status">READY TO VOTE</span>
              <span v-else class="election-status">✓ VOTED</span>
            </div>

            <div class="election-card-footer">
              <Link
                v-if="!election.has_voted"
                :href="getVoteUrl(election.id)"
                class="vote-btn-link"
              >
                <button class="vote-btn">🗳️ Vote Now</button>
              </Link>
              <button v-else class="voted-badge" disabled>✓ You Voted</button>
            </div>
          </div>
        </div>

        <div v-else class="empty-state-banner">
          <div class="empty-state-icon">—</div>
          <div class="empty-state-title">No active elections</div>
          <p class="empty-state-text">There are currently no open elections. Please check back later.</p>
        </div>
      </section>

      <!-- Announcements -->
      <section aria-labelledby="announcements-heading">
        <h2 id="announcements-heading" class="section-header">Latest announcements</h2>
        <div class="announcements-section">
          <div
            v-for="announcement in announcements"
            :key="announcement.id"
            class="announcement-item"
          >
            <strong>{{ formatDate(announcement.created_at) }}:</strong>
            {{ announcement.content }}
          </div>
          <p v-if="!announcements || announcements.length === 0" class="announcements-empty">
            No announcements at this time.
          </p>
        </div>
      </section>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const props = defineProps({
  voter: { type: Object, default: null },
  announcements: { type: Array, default: () => [] },
  activeElections: { type: Array, default: () => [] },
  recentWinners: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };

function formatDate(val) {
  if (!val) return '';
  const d = new Date(val);
  return d.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
  });
}

function getVoteUrl(electionId) {
  return urls.voter.vote + '?election_id=' + electionId;
}
</script>

<style scoped>
.welcome-banner {
  background: linear-gradient(135deg, var(--primary), var(--primary-light));
  color: white;
  padding: 40px;
  border-radius: 16px;
  margin-bottom: 30px;
  box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25);
  position: relative;
  overflow: hidden;
}
.welcome-title {
  font-size: 32px;
  font-weight: 700;
  margin: 0 0 10px 0;
  letter-spacing: 0.5px;
  position: relative;
  z-index: 1;
}
.welcome-subtitle {
  font-size: 16px;
  opacity: 0.95;
  margin: 0;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.95);
  position: relative;
  z-index: 1;
}
.election-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  border-top: 4px solid var(--primary);
  display: flex;
  flex-direction: column;
  border: 1px solid rgba(148, 163, 184, 0.35);
}
.election-card:hover {
  transform: translateY(-4px) scale(1.01);
  box-shadow: 0 12px 35px rgba(15, 23, 42, 0.14);
  border-color: rgba(34, 197, 94, 0.4);
}
.election-card-header {
  padding: 20px;
  background: linear-gradient(135deg, #f8f9fa, #eef2f7);
  border-bottom: 1px solid #eee;
}
.election-card-title {
  font-size: 20px;
  font-weight: 700;
  color: #064e3b;
  margin: 0 0 8px 0;
}
.election-card-date {
  font-size: 12px;
  color: #999;
  margin: 0;
}
.election-card-body {
  padding: 20px;
  flex: 1;
}
.election-stat {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 13px;
}
.election-stat-label {
  color: #6b7280;
  font-weight: 600;
}
.election-stat-value {
  color: #047857;
  font-weight: 700;
}
.election-status {
  display: inline-block;
  background: linear-gradient(135deg, var(--primary), var(--primary-light));
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  margin-top: 10px;
}
.election-card-footer {
  padding: 15px 20px;
  background: #f9f9f9;
  border-top: 1px solid #eee;
}
.vote-btn-link {
  text-decoration: none;
}
.vote-btn {
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #22863a, #2ecc71);
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 700;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.2s ease;
}
.vote-btn:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 12px rgba(34, 134, 58, 0.3);
}
.voted-badge {
  width: 100%;
  padding: 12px;
  background: #e8e8e8;
  color: #666;
  border: none;
  border-radius: 6px;
  font-weight: 700;
  cursor: not-allowed;
  font-size: 14px;
}
.section-header {
  font-size: 22px;
  font-weight: 700;
  color: #064e3b;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.elections-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}
.announcements-section {
  background: white;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}
.announcement-item {
  padding: 15px;
  background: var(--lighter);
  border-left: 4px solid var(--primary);
  border-radius: 4px;
  margin-bottom: 12px;
  font-size: 14px;
  line-height: 1.6;
  color: #333;
}
.announcement-item:last-child {
  margin-bottom: 0;
}
.announcements-empty {
  text-align: center;
  color: #9ca3af;
  padding: 20px;
}
.empty-state-banner {
  background: #f9fafb;
  border: 2px dashed #d1d5db;
  border-radius: 10px;
  padding: 40px;
  text-align: center;
  color: #6b7280;
}
.empty-state-icon {
  font-size: 48px;
  margin-bottom: 15px;
}
.empty-state-title {
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 10px;
}
.empty-state-text {
  font-size: 14px;
  opacity: 0.8;
}
.recent-winners-card {
  margin-bottom: 30px;
  border-left: 5px solid #059669;
}
.recent-winners-title {
  margin: 0 0 10px 0;
  color: #047857;
}
.recent-winners-list {
  margin: 0;
  padding-left: 20px;
  color: #334155;
}
</style>
