<template>
  <DashboardLayout
    page-title="Election Results"
    page-subtitle="View live and past election results."
  >
    <!-- Election picker -->
    <section class="results-hero" aria-label="Choose election">
      <div class="results-hero-top">
        <div class="results-hero-heading">
          <BarChart3 class="results-hero-icon" :size="28" />
          <div>
            <h2 class="results-hero-title">Election results</h2>
            <p class="results-hero-desc">
              Pick an election to see vote totals. Counts stay hidden while voting
              is still open.
            </p>
          </div>
        </div>
        <Link
          :href="urls.voter?.dashboard || '/voter/dashboard'"
          class="results-back-link"
        >
          <ArrowLeft :size="16" />
          Dashboard
        </Link>
      </div>

      <div v-if="elections.length" class="results-picker">
        <div class="results-picker-label">
          <SlidersHorizontal :size="16" />
          Select an election
        </div>
        <div class="results-tabs" role="tablist">
          <Link
            v-for="e in elections"
            :key="e.id"
            :href="resultsTabUrl(e.id)"
            class="results-tab"
            :class="{ 'results-tab--active': election && election.id === e.id }"
            role="tab"
            :aria-selected="election && election.id === e.id"
          >
            {{ e.title }}
          </Link>
        </div>
      </div>
      <div v-else class="results-no-elections">
        <Inbox :size="40" class="results-no-elections-icon" />
        <p>No elections are available yet.</p>
      </div>
    </section>

    <!-- Content -->
    <template v-if="election">
      <!-- Locked: active election -->
      <div v-if="resultsLocked" class="locked-card">
        <div class="locked-card-icon-wrap">
          <Lock :size="36" class="locked-card-icon" />
        </div>
        <h3 class="locked-card-title">Results are not public yet</h3>
        <p class="locked-card-text">
          <strong>{{ election.title }}</strong> is still active. Vote totals and
          rankings will appear here after the election ends.
        </p>
      </div>

      <!-- Full results -->
      <template v-else>
        <header class="results-summary-banner">
          <div>
            <h2 class="results-summary-title">{{ election.title }}</h2>
            <p class="results-summary-meta">
              {{ positionCount }} positions · ranked by vote share
            </p>
          </div>
          <div class="results-stat-cards">
            <div class="results-stat-card">
              <Users :size="22" class="results-stat-card-icon" />
              <span class="results-stat-card-label">Ballots cast</span>
              <span class="results-stat-card-value">{{ totalVotes }}</span>
            </div>
            <div class="results-stat-card results-stat-card--accent">
              <ChartPie :size="22" class="results-stat-card-icon" />
              <span class="results-stat-card-label">Positions</span>
              <span class="results-stat-card-value">{{ positionCount }}</span>
            </div>
          </div>
        </header>

        <div
          v-for="(candidates, positionName) in results"
          :key="positionName"
          class="position-block"
        >
          <h3 class="position-block-title">
            <Target :size="20" class="position-block-title-icon" />
            {{ positionName }}
          </h3>
          <ul class="candidate-list" role="list">
            <li
              v-for="(c, idx) in candidates"
              :key="c.id ?? idx"
              class="candidate-row"
              :class="rankClass(idx)"
            >
              <div class="candidate-rank" :class="'candidate-rank--' + (idx + 1)">
                <Trophy v-if="idx === 0" :size="16" />
                <span v-else>{{ idx + 1 }}</span>
              </div>
              <div class="candidate-avatar">{{ (c.name || "?").charAt(0) }}</div>
              <div class="candidate-body">
                <div class="candidate-name-row">
                  <span class="candidate-name">{{ c.name }}</span>
                  <span class="candidate-pct">{{ c.percentage }}%</span>
                </div>
                <div class="progress-track">
                  <div
                    class="progress-fill"
                    :class="'progress-fill--' + (idx === 0 ? 'lead' : 'rest')"
                    :style="{ width: Math.min(100, c.percentage) + '%' }"
                  />
                </div>
                <span class="candidate-votes-muted"
                  >{{ c.votes }} votes</span
                >
              </div>
            </li>
          </ul>
        </div>
      </template>
    </template>

    <div v-else class="empty-card">
      <Vote :size="48" class="empty-card-icon" />
      <p class="empty-card-title">No election selected</p>
      <p class="empty-card-text">
        Choose an election above to load results (when available).
      </p>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/inertia-vue3";
import DashboardLayout from "../../Layouts/DashboardLayout.vue";
import {
  BarChart3,
  ArrowLeft,
  SlidersHorizontal,
  Lock,
  Users,
  ChartPie,
  Target,
  Trophy,
  Vote,
  Inbox,
} from "lucide-vue-next";

const props = defineProps({
  election: { type: Object, default: null },
  elections: { type: Array, default: () => [] },
  results: { type: Object, default: () => ({}) },
  totalVotes: { type: Number, default: 0 },
  resultsLocked: { type: Boolean, default: false },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };

const positionCount = computed(() => Object.keys(props.results || {}).length);

function resultsTabUrl(electionId) {
  const base = urls.voter?.results;
  const path =
    base && base !== "#" && !String(base).includes("undefined")
      ? base
      : "/voter/results";
  return `${path}?election_id=${electionId}`;
}

function rankClass(index) {
  if (index === 0) return "candidate-row--gold";
  if (index === 1) return "candidate-row--silver";
  if (index === 2) return "candidate-row--bronze";
  return "";
}
</script>

<style scoped>
.results-hero {
  background: linear-gradient(
    135deg,
    var(--primary, #059669),
    var(--primary-light, #10b981)
  );
  color: #fff;
  border-radius: 20px;
  padding: 28px 28px 24px;
  margin-bottom: 28px;
  box-shadow: 0 16px 40px rgba(5, 150, 105, 0.22);
}
.results-hero-top {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 22px;
}
.results-hero-heading {
  display: flex;
  gap: 16px;
  align-items: flex-start;
  max-width: 640px;
}
.results-hero-icon {
  flex-shrink: 0;
  opacity: 0.95;
}
.results-hero-title {
  margin: 0 0 6px;
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: -0.02em;
}
.results-hero-desc {
  margin: 0;
  color: white;
  font-size: 0.9rem;
  line-height: 1.5;
  opacity: 0.95;
}
.results-back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  background: rgba(255, 255, 255, 0.18);
  border: 1px solid rgba(255, 255, 255, 0.35);
  border-radius: 10px;
  color: #fff;
  font-weight: 600;
  font-size: 0.875rem;
  text-decoration: none;
  transition: background 0.2s;
}
.results-back-link:hover {
  background: rgba(255, 255, 255, 0.28);
}

.results-picker {
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.22);
  border-radius: 14px;
  padding: 18px 18px 16px;
}
.results-picker-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  opacity: 0.9;
  margin-bottom: 12px;
}
.results-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.results-tab {
  padding: 10px 18px;
  background: rgba(255, 255, 255, 0.15);
  color: #fff;
  border: 2px solid rgba(255, 255, 255, 0.35);
  border-radius: 999px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.875rem;
  transition:
    transform 0.15s,
    background 0.2s,
    border-color 0.2s;
  max-width: 100%;
  text-align: center;
}
.results-tab:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-1px);
}
.results-tab--active {
  background: #fff;
  color: #065f46;
  border-color: #fff;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}

.results-no-elections {
  text-align: center;
  padding: 20px;
  opacity: 0.9;
  font-size: 0.95rem;
}
.results-no-elections-icon {
  margin: 0 auto 12px;
  opacity: 0.7;
  display: block;
}

.locked-card {
  background: linear-gradient(180deg, #fffbeb 0%, #fff 100%);
  border: 1px solid #fcd34d;
  border-radius: 18px;
  padding: 36px 28px;
  text-align: center;
  margin-bottom: 24px;
  box-shadow: 0 8px 28px rgba(245, 158, 11, 0.12);
}
.locked-card-icon-wrap {
  width: 72px;
  height: 72px;
  margin: 0 auto 16px;
  border-radius: 50%;
  background: linear-gradient(135deg, #fef3c7, #fde68a);
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #fbbf24;
}
.locked-card-icon {
  color: #b45309;
}
.locked-card-title {
  margin: 0 0 10px;
  font-size: 1.35rem;
  font-weight: 800;
  color: #92400e;
}
.locked-card-text {
  margin: 0;
  color: #78350f;
  line-height: 1.6;
  max-width: 520px;
  margin-inline: auto;
  font-size: 0.95rem;
}

.results-summary-banner {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  background: linear-gradient(
    135deg,
    var(--primary, #059669),
    var(--primary-light, #10b981)
  );
  color: #fff;
  padding: 28px 30px;
  border-radius: 18px;
  margin-bottom: 24px;
  box-shadow: 0 12px 32px rgba(13, 148, 136, 0.25);
}
.results-summary-title {
  margin: 0 0 6px;
  font-size: 1.65rem;
  font-weight: 800;
  letter-spacing: -0.02em;
}
.results-summary-meta {
  margin: 0;
  color: white;
  font-size: 0.875rem;
  opacity: 0.92;
}
.results-stat-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}
.results-stat-card {
  min-width: 130px;
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 14px;
  padding: 14px 18px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.results-stat-card--accent {
  background: rgba(255, 255, 255, 0.22);
}
.results-stat-card-icon {
  opacity: 0.9;
  margin-bottom: 4px;
}
.results-stat-card-label {
  font-size: 0.65rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  opacity: 0.85;
  font-weight: 700;
}
.results-stat-card-value {
  font-size: 1.5rem;
  font-weight: 800;
}

.position-block {
  background: #fff;
  border-radius: 16px;
  padding: 22px 24px 18px;
  margin-bottom: 20px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
  border-left: 5px solid #059669;
}
.position-block-title {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.15rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 18px;
  letter-spacing: -0.02em;
}
.position-block-title-icon {
  color: #0d9488;
  flex-shrink: 0;
}

.candidate-list {
  list-style: none;
  margin: 0;
  padding: 0;
}
.candidate-row {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  padding: 14px 12px;
  border-radius: 12px;
  margin-bottom: 10px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  transition: box-shadow 0.2s;
}
.candidate-row:last-child {
  margin-bottom: 0;
}
.candidate-row:hover {
  box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
}
.candidate-row--gold {
  background: linear-gradient(135deg, #fffbeb 0%, #fef9c3 100%);
  border-color: #fde047;
}
.candidate-row--silver {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-color: #cbd5e1;
}
.candidate-row--bronze {
  background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
  border-color: #fdba74;
}

.candidate-rank {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 800;
  background: #e2e8f0;
  color: #475569;
  flex-shrink: 0;
}
.candidate-rank--1 {
  background: linear-gradient(135deg, #facc15, #eab308);
  color: #713f12;
}
.candidate-rank--2 {
  background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
  color: #334155;
}
.candidate-rank--3 {
  background: linear-gradient(135deg, #fdba74, #fb923c);
  color: #7c2d12;
}

.candidate-avatar {
  width: 44px;
  height: 44px;
  background: linear-gradient(135deg, #0d9488, #0891b2);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #fff;
  flex-shrink: 0;
  font-size: 1rem;
}
.candidate-body {
  flex: 1;
  min-width: 0;
}
.candidate-name-row {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  gap: 12px;
  margin-bottom: 8px;
}
.candidate-name {
  font-weight: 700;
  color: #1e293b;
  font-size: 1rem;
}
.candidate-pct {
  font-weight: 800;
  color: #059669;
  font-size: 1.05rem;
  flex-shrink: 0;
}
.progress-track {
  height: 8px;
  background: #e2e8f0;
  border-radius: 999px;
  overflow: hidden;
  margin-bottom: 6px;
}
.progress-fill {
  height: 100%;
  border-radius: 999px;
  transition: width 0.35s ease;
}
.progress-fill--lead {
  background: linear-gradient(90deg, #059669, #10b981);
}
.progress-fill--rest {
  background: linear-gradient(90deg, #14b8a6, #2dd4bf);
}
.candidate-votes-muted {
  font-size: 0.8rem;
  color: #64748b;
}

.empty-card {
  text-align: center;
  padding: 48px 24px;
  background: #fff;
  border-radius: 16px;
  border: 1px dashed #cbd5e1;
}
.empty-card-icon {
  color: #94a3b8;
  margin: 0 auto 16px;
  display: block;
}
.empty-card-title {
  font-size: 1.2rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0 0 8px;
}
.empty-card-text {
  margin: 0;
  color: #64748b;
  font-size: 0.95rem;
}
</style>
