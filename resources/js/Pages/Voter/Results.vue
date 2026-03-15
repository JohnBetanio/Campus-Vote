<template>
  <DashboardLayout page-title="Election Results" page-subtitle="View live and past election results.">
    <div class="results-selector-banner">
      <div class="results-selector-title">Select an election</div>
      <div class="results-tabs">
        <Link
          v-for="e in elections"
          :key="e.id"
          :href="urls.voter.results + '?election_id=' + e.id"
          class="results-tab"
          :class="{ active: election && election.id === e.id }"
        >
          {{ e.title }}
        </Link>
      </div>
    </div>

    <template v-if="election">
      <div v-if="resultsLocked" class="card">
        <p class="empty-state-title">Results are hidden while this election is active.</p>
        <p class="empty-state-text">Results will be visible after the election ends.</p>
      </div>

      <template v-else>
        <div class="results-header">
          <h2 class="results-title">{{ election.title }}</h2>
          <div class="results-info-grid">
            <div class="info-item">
              <span class="info-icon">📊</span>
              <span class="info-label">Total votes</span>
              <span class="info-value">{{ totalVotes }}</span>
            </div>
          </div>
        </div>

        <div v-for="(candidates, positionName) in results" :key="positionName" class="position-results-card">
          <h3 class="position-title">🎯 {{ positionName }}</h3>
          <div v-for="(c, idx) in candidates" :key="idx" class="candidate-result">
            <div class="candidate-header">
              <div class="candidate-avatar">{{ (c.name || '?').charAt(0) }}</div>
              <div class="result-info">
                <div class="result-name">{{ c.name }}</div>
                <div class="result-votes">{{ c.votes }} votes ({{ c.percentage }}%)</div>
              </div>
              <span class="result-percentage">{{ c.percentage }}%</span>
            </div>
            <div class="progress-bar-container">
              <div class="progress-bar" :style="{ width: c.percentage + '%' }"></div>
            </div>
          </div>
        </div>
      </template>
    </template>

    <div v-else class="card">
      <div class="empty-state">
        <p class="empty-state-title">No election selected</p>
        <p class="empty-state-text">Select an election above to view results.</p>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

defineProps({
  election: { type: Object, default: null },
  elections: { type: Array, default: () => [] },
  results: { type: Object, default: () => ({}) },
  totalVotes: { type: Number, default: 0 },
  resultsLocked: { type: Boolean, default: false },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };
</script>

<style scoped>
.results-selector-banner { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; }
.results-selector-title { font-size: 18px; font-weight: 700; margin-bottom: 15px; }
.results-tabs { display: flex; gap: 10px; flex-wrap: wrap; }
.results-tab { padding: 10px 20px; background: rgba(255,255,255,0.2); color: white; border: 2px solid white; border-radius: 6px; text-decoration: none; font-weight: 600; }
.results-tab.active { background: white; color: #1a7a8f; }
.results-header { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; padding: 35px; border-radius: 12px; margin-bottom: 30px; }
.results-title { font-size: 28px; font-weight: 700; margin: 0 0 10px 0; }
.results-info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.3); }
.info-item { display: flex; align-items: center; gap: 12px; }
.info-value { font-size: 16px; font-weight: 700; display: block; }
.position-results-card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-top: 4px solid #24a1b8; }
.position-title { font-size: 22px; font-weight: 700; color: #333; margin: 0 0 20px 0; }
.candidate-result { margin-bottom: 24px; }
.candidate-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.candidate-avatar { width: 44px; height: 44px; background: linear-gradient(135deg, #1a7a8f, #24a1b8); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; }
.result-name { font-size: 16px; font-weight: 600; margin-bottom: 4px; }
.result-votes { font-size: 14px; color: #666; }
.result-percentage { font-size: 18px; font-weight: 700; min-width: 60px; text-align: right; }
.progress-bar-container { height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
.progress-bar { height: 100%; background: linear-gradient(90deg, #059669, #10b981); border-radius: 4px; transition: width 0.3s; }
</style>
