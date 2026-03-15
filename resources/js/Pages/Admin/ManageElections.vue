<template>
  <DashboardLayout
    page-title="Manage Elections"
    page-subtitle="Create, edit, and monitor all elections in the system."
  >
    <template #actions>
      <Link :href="urls.admin.elections_create" class="btn btn-primary">+ Create New Election</Link>
    </template>

    <div id="electionsContainer">
      <div
        v-for="election in elections"
        :key="election.id"
        class="election-card"
        :class="{ ended: election.status === 'ended' }"
      >
        <div class="election-header">
          <div>
            <h2 class="election-title">{{ election.title }}</h2>
            <small class="election-meta-text">Created {{ formatDate(election.created_at) }}</small>
          </div>
          <div>
            <span class="status-badge" :class="election.status">
              <span>●</span>
              {{ election.status === 'active' ? 'Active' : 'Ended' }}
            </span>
          </div>
        </div>

        <div class="election-meta">
          <div class="meta-item">
            <span class="meta-value">{{ election.positions_count }}</span>
            <span class="meta-label">Positions</span>
          </div>
          <div class="meta-item">
            <span class="meta-value">{{ election.candidates_count }}</span>
            <span class="meta-label">Candidates</span>
          </div>
          <div class="meta-item">
            <span class="meta-value">{{ election.votes_cast }}</span>
            <span class="meta-label">Votes Cast</span>
          </div>
          <div class="meta-item">
            <span class="meta-value">{{ election.participation_rate }}%</span>
            <span class="meta-label">Participation</span>
          </div>
        </div>

        <div v-if="election.positions && election.positions.length > 0" class="positions-section">
          <div class="positions-label">Positions & Candidates</div>
          <div class="position-grid">
            <div
              v-for="position in election.positions"
              :key="position.id"
              class="position-card"
              :class="{ ended: election.status === 'ended' }"
            >
              <div class="position-name">🎯 {{ position.name }}</div>
              <div class="candidate-list">
                <div v-for="candidate in position.candidates" :key="candidate.id" class="candidate-item">
                  {{ candidate.name }}
                </div>
                <div v-if="!position.candidates || position.candidates.length === 0" class="candidate-empty">
                  No candidates
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="action-group">
          <Link :href="election.edit_url" class="action-btn action-btn-edit">✏️ Edit</Link>
          <form
            v-if="election.status === 'active'"
            :action="election.end_url"
            method="POST"
            class="action-form"
            @submit="onEndElection"
          >
            <input type="hidden" name="_token" :value="csrfToken" />
            <button type="submit" class="action-btn action-btn-end">🛑 End Election</button>
          </form>
          <form :action="election.destroy_url" method="POST" class="action-form" @submit="onDeleteElection">
            <input type="hidden" name="_token" :value="csrfToken" />
            <input type="hidden" name="_method" value="DELETE" />
            <button type="submit" class="action-btn action-btn-delete">🗑️ Delete</button>
          </form>
        </div>
      </div>

      <div v-if="elections.length === 0" class="card">
        <div class="empty-elections-state">
          <div class="empty-elections-icon">📋</div>
          <h3 class="empty-elections-title">No Elections Created</h3>
          <p class="empty-elections-text">
            Start by creating your first election to begin the voting process.
          </p>
          <Link :href="urls.admin.elections_create" class="btn btn-primary">➕ Create Election</Link>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const props = defineProps({
  elections: { type: Array, default: () => [] },
  activeElections: { type: Array, default: () => [] },
  endedElections: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { admin: {} };
const csrfToken =
  typeof document !== 'undefined'
    ? document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    : '';

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

function onEndElection(e) {
  if (!confirm('End this election?')) e.preventDefault();
}

function onDeleteElection(e) {
  if (!confirm('Delete this election permanently?')) e.preventDefault();
}
</script>

<style scoped>
.election-card {
  background: white;
  border-radius: 16px;
  padding: 30px;
  margin-bottom: 25px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
  border: 1px solid #e5e7eb;
  border-left: 5px solid var(--primary);
}
.election-card:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  transform: translateY(-4px);
  border-left-color: var(--primary-light);
}
.election-card.ended {
  border-left-color: #999;
  opacity: 0.85;
  background: #f9fafb;
}
.election-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
  gap: 20px;
}
.election-title {
  font-size: 24px;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 8px;
}
.election-card.ended .election-title {
  color: #666;
}
.election-meta-text {
  color: #6b7280;
}
.election-meta {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
  padding: 15px;
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%);
  border-radius: 10px;
  border: 1px solid rgba(5, 150, 105, 0.2);
}
.meta-item {
  text-align: center;
}
.meta-value {
  font-size: 24px;
  font-weight: 700;
  color: #059669;
  display: block;
  margin-bottom: 4px;
}
.election-card.ended .meta-value {
  color: #999;
}
.meta-label {
  font-size: 11px;
  color: #666;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.status-badge.active {
  background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
  color: #065f46;
  border: 1px solid rgba(5, 150, 105, 0.3);
}
.status-badge.ended {
  background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
  color: #666;
  border: 1px solid rgba(0, 0, 0, 0.1);
}
.positions-section {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #e5e7eb;
}
.positions-label {
  font-size: 14px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.position-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 12px;
  margin-top: 15px;
}
.position-card {
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf3 100%);
  padding: 15px;
  border-radius: 10px;
  border-left: 4px solid #059669;
  border: 1px solid rgba(5, 150, 105, 0.2);
}
.position-card.ended {
  border-left-color: #999;
  opacity: 0.8;
}
.position-name {
  font-weight: 700;
  color: #047857;
  margin-bottom: 10px;
  font-size: 13px;
}
.candidate-list {
  font-size: 12px;
  color: #666;
  line-height: 1.8;
}
.candidate-item {
  padding: 4px 0;
  display: flex;
  align-items: center;
  gap: 6px;
}
.candidate-item::before {
  content: '•';
  color: #059669;
  font-weight: bold;
}
.candidate-empty {
  color: #999;
  font-style: italic;
}
.action-group {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 15px;
}
.action-form {
  display: inline;
}
.action-btn {
  padding: 10px 18px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.action-btn-edit {
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  color: #0c4a6e;
  border: 1px solid rgba(3, 102, 214, 0.2);
}
.action-btn-edit:hover {
  background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(3, 102, 214, 0.15);
}
.action-btn-end {
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  color: #92400e;
  border: 1px solid rgba(245, 158, 11, 0.3);
}
.action-btn-end:hover {
  background: linear-gradient(135deg, #fde68a 0%, #fcd34d 100%);
  transform: translateY(-2px);
}
.action-btn-delete {
  background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
  color: #991b1b;
  border: 1px solid rgba(220, 38, 38, 0.2);
}
.action-btn-delete:hover {
  background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
}
.empty-elections-state {
  text-align: center;
  padding: 60px 40px;
}
.empty-elections-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.5;
}
.empty-elections-title {
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 10px;
}
.empty-elections-text {
  font-size: 16px;
  color: #6b7280;
  margin-bottom: 30px;
}
</style>
