<template>
  <DashboardLayout page-title="Vote Now" page-subtitle="Cast your votes carefully. Once submitted, votes cannot be changed.">
    <div class="mx-auto flex max-w-6xl flex-col gap-6">
      <section v-if="activeElections.length > 1" class="vote-election-selector">
        <h2 class="vote-selector-title">📋 Select an election to vote in</h2>
        <div class="vote-selector-tabs">
          <Link
            v-for="elec in activeElections"
            :key="elec.id"
            :href="urls.voter.vote + '?election_id=' + elec.id"
            class="vote-tab"
            :class="{ active: elec.id === election.id }"
          >
            {{ elec.title }}
          </Link>
        </div>
      </section>

      <header class="vote-header">
        <h1 class="vote-header-title">🗳️ {{ election.title }}</h1>
        <p class="vote-header-subtitle">Cast your votes carefully. Once submitted, votes cannot be changed.</p>
      </header>

      <div v-if="validationMessage" class="vote-validation-alert">
        <span>⚠️</span>
        <p>{{ validationMessage }}</p>
      </div>

      <form @submit.prevent="submitVote" class="vote-form">
        <input type="hidden" name="election_id" :value="election.id" />
        <div class="vote-positions-grid">
          <section
            v-for="position in election.positions"
            :key="position.id"
            class="position-card"
            :class="{ complete: selectedCount(position.id) === (position.max_votes || 1) }"
          >
            <div class="position-card-header">
              <h3 class="position-name">{{ position.name }}</h3>
              <span class="position-max">Select up to {{ position.max_votes || 1 }}</span>
            </div>
            <div class="position-status">
              <span class="status-dot" :class="{ complete: selectedCount(position.id) === (position.max_votes || 1) }"></span>
              {{ selectedCount(position.id) }} / {{ position.max_votes || 1 }} selected
            </div>
            <div class="candidates-list">
              <label
                v-for="candidate in position.candidates"
                :key="candidate.id"
                class="candidate-card"
                :class="{ selected: isSelected(position.id, candidate.id) }"
              >
                <input
                  type="checkbox"
                  :value="candidate.id"
                  :name="'votes[' + position.id + '][]'"
                  v-model="votes[position.id]"
                  :data-max="position.max_votes || 1"
                  @change="onCandidateChange(position, $event)"
                />
                <span class="candidate-avatar">{{ (candidate.name || '?').charAt(0) }}</span>
                <span class="candidate-name">{{ candidate.name }}</span>
              </label>
            </div>
          </section>
        </div>

        <div class="form-actions vote-actions">
          <Link :href="urls.voter.dashboard" class="btn btn-secondary">← Back to dashboard</Link>
          <button type="submit" class="btn btn-success" :disabled="!canSubmit">
            {{ form.processing ? 'Submitting...' : '✓ Submit your vote' }}
          </button>
        </div>
      </form>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import { ref, computed, watch } from 'vue';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const props = defineProps({
  election: { type: Object, required: true },
  activeElections: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };

const validationMessage = ref('');
const votes = ref(
  Object.fromEntries((props.election.positions || []).map((p) => [p.id, []]))
);

const form = useForm({
  election_id: props.election.id,
  votes: {},
});

watch(
  votes,
  (v) => {
    const flat = {};
    Object.keys(v).forEach((posId) => {
      flat[posId] = v[posId] || [];
    });
    form.votes = flat;
  },
  { deep: true }
);

function selectedCount(positionId) {
  return (votes.value[positionId] || []).length;
}

function isSelected(positionId, candidateId) {
  return (votes.value[positionId] || []).includes(candidateId);
}

function onCandidateChange(position, event) {
  const max = position.max_votes || 1;
  const arr = votes.value[position.id] || [];
  if (arr.length > max) {
    arr.pop();
    votes.value[position.id] = [...arr];
  }
}

const canSubmit = computed(() => {
  return props.election.positions.every((p) => selectedCount(p.id) === (p.max_votes || 1));
});

function submitVote() {
  if (!canSubmit.value) {
    validationMessage.value = 'Please select the required number of candidates for each position.';
    return;
  }
  form.election_id = props.election.id;
  form.votes = votes.value;
  form.post(urls.voter.submit_vote);
}
</script>

<style scoped>
.vote-election-selector { background: linear-gradient(135deg, #0ea5e9, #06b6d4); color: white; padding: 24px; border-radius: 16px; }
.vote-selector-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; }
.vote-selector-tabs { display: flex; flex-wrap: wrap; gap: 8px; }
.vote-tab { padding: 8px 16px; border-radius: 999px; border: 2px solid rgba(255,255,255,0.6); background: rgba(255,255,255,0.1); color: white; text-decoration: none; font-size: 14px; }
.vote-tab.active { background: white; color: #0c4a6e; }
.vote-tab:hover { background: rgba(255,255,255,0.2); }
.vote-header { background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 24px; border-radius: 16px; }
.vote-header-title { font-size: 24px; font-weight: 600; margin: 0 0 8px 0; }
.vote-header-subtitle { font-size: 14px; opacity: 0.95; margin: 0; }
.vote-validation-alert { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; padding: 12px 16px; border-radius: 8px; display: flex; gap: 8px; align-items: flex-start; }
.vote-positions-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
.position-card { background: white; padding: 20px; border-radius: 16px; border: 1px solid #e5e7eb; }
.position-card.complete { border-color: #10b981; background: #f0fdf4; }
.position-card-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
.position-name { font-size: 16px; font-weight: 600; color: #047857; margin: 0; }
.position-max { font-size: 12px; background: #ecfdf5; color: #047857; padding: 4px 12px; border-radius: 999px; font-weight: 600; }
.position-status { font-size: 12px; color: #64748b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
.status-dot { width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; }
.status-dot.complete { background: #10b981; }
.candidates-list { display: flex; flex-direction: column; gap: 8px; }
.candidate-card { display: flex; align-items: center; gap: 12px; padding: 12px; border: 2px solid #e5e7eb; border-radius: 12px; cursor: pointer; background: #f8fafc; transition: all 0.2s; }
.candidate-card.selected { border-color: #10b981; background: #ecfdf5; }
.candidate-card input { width: 18px; height: 18px; }
.candidate-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #059669, #10b981); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; }
.candidate-name { font-weight: 600; color: #1e293b; }
.vote-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px; padding: 20px; background: white; border-radius: 16px; border: 1px solid #e5e7eb; }
</style>
