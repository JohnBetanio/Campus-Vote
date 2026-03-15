<template>
  <DashboardLayout page-title="Create Election" page-subtitle="Set up a new election with positions and candidates">
    <div class="form-header">
      <h1>Create New Election</h1>
      <p>Set up a new election with positions and candidates</p>
    </div>

    <form @submit.prevent="form.post(urls.admin.elections_store)">
      <div class="election-form">
        <div class="section-card">
          <h2 class="section-header">
            <span class="section-header-icon">📋</span>
            Election Details
          </h2>
          <div class="form-group">
            <label class="form-label" for="title">Election Title *</label>
            <input id="title" v-model="form.title" type="text" class="form-control" placeholder="Enter election name" required />
            <p v-if="form.errors.title" class="form-error">{{ form.errors.title }}</p>
          </div>
        </div>

        <div v-for="(position, index) in form.positions" :key="index" class="section-card">
          <h2 class="section-header">
            <span class="section-header-icon">{{ index + 1 }}</span>
            Position {{ index + 1 }}
          </h2>
          <div class="position-controls">
            <div>
              <label class="form-label">Position Name *</label>
              <input v-model="position.name" type="text" class="form-control" placeholder="e.g., President, Secretary" required />
            </div>
            <div style="min-width: 150px">
              <label class="form-label">Max Votes Allowed</label>
              <input v-model.number="position.max_votes" type="number" class="form-control" min="1" />
            </div>
            <button type="button" class="btn btn-danger btn-sm" style="align-self: flex-end" @click="removePosition(index)">🗑️ Remove</button>
          </div>
          <div class="candidates-section">
            <div class="candidates-label">Candidates</div>
            <div v-for="(cand, cIdx) in position.candidates" :key="cIdx" class="candidate-input-group">
              <input v-model="position.candidates[cIdx]" type="text" class="form-control" :placeholder="`Candidate ${cIdx + 1} Name`" required />
              <button type="button" class="btn btn-danger btn-sm" @click="removeCandidate(index, cIdx)">✕ Delete</button>
            </div>
            <button type="button" class="btn-add small" style="margin-top: 12px" @click="addCandidate(index)">➕ Add Candidate</button>
          </div>
        </div>

        <div style="text-align: center; margin-bottom: 20px">
          <button type="button" class="btn-add" @click="addPosition">➕ Add Position</button>
        </div>

        <div class="form-actions">
          <Link :href="urls.admin.elections_index" class="btn btn-secondary">Cancel</Link>
          <button type="submit" class="btn btn-primary" :disabled="form.processing">
            {{ form.processing ? 'Creating...' : 'Create Election' }}
          </button>
        </div>
      </div>
    </form>
  </DashboardLayout>
</template>

<script setup>
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const page = usePage();
const urls = page.props.urls || { admin: {} };

const form = useForm({
  title: '',
  positions: [{ name: '', max_votes: 1, candidates: [''] }],
});

function addPosition() {
  form.positions.push({ name: '', max_votes: 1, candidates: [''] });
}

function removePosition(index) {
  form.positions.splice(index, 1);
}

function addCandidate(positionIndex) {
  const pos = form.positions[positionIndex];
  if (!pos.candidates) pos.candidates = [''];
  pos.candidates.push('');
}

function removeCandidate(positionIndex, candidateIndex) {
  form.positions[positionIndex].candidates.splice(candidateIndex, 1);
}
</script>

<style scoped>
.election-form { max-width: 900px; margin: 0 auto; }
.form-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; padding: 40px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25); }
.form-header h1 { font-size: 32px; font-weight: 700; margin: 0 0 10px 0; }
.form-header p { font-size: 16px; opacity: 0.95; margin: 0; }
.section-card { background: white; border-radius: 16px; padding: 30px; margin-bottom: 25px; border: 1px solid #e5e7eb; }
.section-header { font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
.section-header-icon { width: 36px; height: 36px; background: linear-gradient(135deg, #047857 0%, #059669 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; }
.position-controls { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 15px; align-items: flex-end; }
.candidates-section { background: linear-gradient(135deg, rgba(5, 150, 105, 0.03) 0%, rgba(5, 150, 105, 0.01) 100%); padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid rgba(5, 150, 105, 0.1); }
.candidates-label { font-weight: 700; color: #047857; margin-bottom: 15px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
.candidate-input-group { display: flex; gap: 12px; margin-bottom: 12px; align-items: center; }
.btn-add, .add-candidate-btn { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border-radius: 10px; padding: 12px 24px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; }
.btn-add.small { padding: 8px 16px; font-size: 13px; }
.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 40px; padding-top: 30px; border-top: 2px solid #e5e7eb; }
</style>
