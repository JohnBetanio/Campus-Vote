<template>
  <DashboardLayout page-title="Edit Election" page-subtitle="">
  <div class="card">
    <form @submit.prevent="form.put(updateUrl)">
      <div
        style="background: #e8e8e8; padding: 20px; border-radius: 6px; margin-bottom: 20px;"
      >
        <label class="form-label">Election Title:</label>
        <input
          v-model="form.title"
          type="text"
          class="form-control"
          required
          style="margin-bottom: 15px;"
        />
        <p v-if="form.errors.title" class="form-error">{{ form.errors.title }}</p>

        <button type="button" class="btn btn-success" @click="addPosition">
          + Add Position
        </button>
      </div>

      <div id="positionsContainer">
        <div
          v-for="(position, index) in form.positions"
          :key="index"
          class="position-section"
          :id="`position-${index}`"
        >
          <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;"
          >
            <div style="flex: 1; margin-right: 10px;">
              <label class="form-label">Position Name:</label>
              <input
                v-model="position.name"
                type="text"
                class="form-control"
                required
              />
              <p v-if="form.errors[`positions.${index}.name`]" class="form-error">
                {{ form.errors[`positions.${index}.name`] }}
              </p>
              <label class="form-label" style="margin-top: 8px;"
                >Max selections for this position:</label
              >
              <input
                v-model.number="position.max_votes"
                type="number"
                class="form-control"
                min="1"
                style="width: 120px;"
              />
            </div>
            <button
              type="button"
              class="btn btn-danger btn-sm"
              style="margin-top: 25px;"
              @click="removePosition(index)"
            >
              Delete
            </button>
          </div>

          <div :id="`candidates-${index}`">
            <div
              v-for="(candidate, cIndex) in position.candidates"
              :key="cIndex"
              class="candidate-input-group"
            >
              <input
                v-model="position.candidates[cIndex]"
                type="text"
                class="form-control"
                required
              />
              <button
                type="button"
                class="btn btn-danger btn-sm"
                @click="removeCandidate(index, cIndex)"
              >
                Delete
              </button>
            </div>
          </div>

          <button
            type="button"
            class="btn btn-add btn-sm"
            @click="addCandidate(index)"
          >
            + Add Candidate
          </button>
        </div>
      </div>

      <div class="form-actions">
        <Link :href="cancelUrl" class="btn btn-secondary">Cancel</Link>
        <button
          type="submit"
          class="btn btn-success"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Updating...' : 'Update Election' }}
        </button>
      </div>
    </form>
  </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const props = defineProps({
  election: {
    type: Object,
    required: true,
  },
  updateUrl: {
    type: String,
    required: true,
  },
  cancelUrl: {
    type: String,
    required: true,
  },
});

const form = useForm({
  title: props.election.title,
  positions: props.election.positions.map((p) => ({
    name: p.name,
    max_votes: p.max_votes ?? 1,
    candidates: (p.candidates || []).map((c) =>
      typeof c === 'string' ? c : c.name
    ),
  })),
});

function addPosition() {
  form.positions.push({
    name: '',
    max_votes: 1,
    candidates: [''],
  });
}

function removePosition(index) {
  if (confirm('Are you sure you want to remove this position?')) {
    form.positions.splice(index, 1);
  }
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
