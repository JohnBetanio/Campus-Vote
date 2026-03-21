<template>
  <DashboardLayout page-title="Your Vote History" page-subtitle="Review all your submitted votes across past elections.">
    <div class="votes-header">
      <h1 class="votes-header-title">
        <FileText class="inline-block mr-2" :size="24" />
        Your Vote History
      </h1>
      <p class="votes-header-subtitle">Review all your submitted votes across all elections</p>
    </div>

    <template v-if="votesByElection.length">
      <div v-for="item in votesByElection" :key="item.election.id" class="election-votes-group">
        <h3 class="election-title-header">{{ item.election.title }}</h3>
        <p class="election-date-info">
          <Clock class="inline-block mr-2" :size="14" />
          Election Created: {{ formatDate(item.election.created_at) }}
        </p>
        <div class="votes-grid">
          <div v-for="(positionVotes, positionName) in item.votes" :key="positionName">
            <div v-for="(vote, vIdx) in positionVotes" :key="vIdx" class="vote-card">
              <div class="position-name">{{ positionName }}</div>
              <div class="candidate-voted">
                <div class="candidate-avatar">
                  <User :size="20" />
                </div>
                <div class="candidate-name">{{ vote.candidate_name }}</div>
              </div>
              <span class="vote-badge">
                <Check class="inline-block mr-1" :size="14" />
                Voted
              </span>
              <div class="vote-timestamp">
                <Clock class="inline-block mr-2" :size="14" />
                {{ formatDate(vote.created_at) }}
              </div>
            </div>
          </div>
        </div>
        <div class="votes-summary">
          <strong>{{ item.positions_count }}</strong> Position(s) • <strong>{{ item.votes_count }}</strong> Vote(s) Cast
        </div>
      </div>
    </template>

    <div v-else class="empty-state-container">
      <div class="empty-state-icon">
        <Vote :size="48" />
      </div>
      <div class="empty-state-title">No Votes Yet</div>
      <p class="empty-state-text">You haven't cast any votes yet. Participate in active elections to see your voting history here.</p>
      <Link :href="urls.voter.dashboard" class="back-btn">
        <ArrowLeft class="inline-block mr-2" :size="16" />
        Back to Dashboard
      </Link>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import { FileText, Clock, User, Check, Vote, ArrowLeft } from 'lucide-vue-next';

defineProps({
  votesByElection: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { voter: {} };

function formatDate(val) {
  if (!val) return '';
  return new Date(val).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' });
}
</script>

<style scoped>
.votes-header { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; padding: 35px; border-radius: 12px; margin-bottom: 30px; }
.votes-header-title { font-size: 28px; font-weight: 700; margin: 0 0 10px 0; }
.votes-header-subtitle { font-size: 14px; opacity: 0.95; margin: 0; }
.election-votes-group { background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 5px solid var(--primary); }
.election-title-header { font-size: 22px; font-weight: 700; color: #333; margin: 0 0 5px 0; }
.election-date-info { font-size: 12px; color: #999; margin-bottom: 20px; }
.votes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
.vote-card { background: linear-gradient(135deg, #f8f9fa, #eef2f7); border-radius: 10px; padding: 20px; border: 2px solid #e0e0e0; }
.position-name { font-size: 12px; font-weight: 700; color: #999; text-transform: uppercase; margin-bottom: 10px; }
.candidate-voted { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
.candidate-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, #1a7a8f, #24a1b8); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.candidate-name { font-weight: 700; color: #333; font-size: 14px; }
.vote-badge { display: inline-block; background: linear-gradient(135deg, #1a7a8f, #24a1b8); color: white; padding: 6px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-top: 10px; }
.vote-timestamp { font-size: 11px; color: #666; margin-top: 12px; padding-top: 12px; border-top: 1px solid #e0e0e0; }
.votes-summary { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 13px; color: #666; }
.empty-state-container { text-align: center; padding: 60px 20px; background: white; border-radius: 12px; }
.empty-state-icon { font-size: 64px; margin-bottom: 20px; }
.empty-state-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; }
.empty-state-text { font-size: 16px; color: #666; margin-bottom: 30px; }
.back-btn { padding: 12px 24px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px; font-weight: 700; display: inline-block; }
</style>
