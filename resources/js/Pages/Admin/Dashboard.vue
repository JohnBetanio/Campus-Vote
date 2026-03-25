<template>
    <DashboardLayout
        page-title="Dashboard"
        page-subtitle="Monitor elections, voters, and participation in real-time."
    >
        <template #actions>
            <Link :href="urls.admin?.elections_create || '/admin/elections/create'" 
                   class="btn btn-primary">
                + Add Election
            </Link>
            <Link :href="urls.admin?.elections_index || '/admin/elections'" 
                   class="btn btn-secondary">
                Manage Elections
            </Link>
        </template>

        <div
            class="dashboard-content"
            style="max-width: 1280px; margin: 0 auto"
        >
            <!-- KPI Cards -->
            <section class="kpi-grid" aria-label="Key metrics">
                <Link
                    :href="urls.admin.elections_index"
                    class="kpi-card kpi-card--primary"
                    :title="`View all elections (${activeElectionsCount} active)`"
                    :aria-label="`Total elections: ${totalElections}, ${activeElectionsCount} active. Go to manage elections.`"
                >
                    <div class="kpi-card__icon" aria-hidden="true">
                        <FileText :size="24" />
                    </div>
                    <div class="kpi-card__value">{{ totalElections }}</div>
                    <div class="kpi-card__label">Total Elections</div>
                    <div class="kpi-card__detail">
                        {{ activeElectionsCount }} active •
                        {{
                            Math.max(0, totalElections - activeElectionsCount)
                        }}
                        ended
                    </div>
                </Link>

                <Link
                    :href="urls.admin.voters_index"
                    class="kpi-card"
                    title="View registered voters"
                    :aria-label="`Registered voters: ${totalVoters}, ${totalVotedCount} ${totalVotedCount === 1 ? 'has' : 'have'} participated.`"
                >
                    <div class="kpi-card__icon" aria-hidden="true">
                        <Users :size="24" />
                    </div>
                    <div class="kpi-card__value">{{ totalVoters }}</div>
                    <div class="kpi-card__label">Registered Voters</div>
                    <div class="kpi-card__detail">
                        {{ totalVotedCount }}
                        {{
                            totalVotedCount === 1 ? "has" : "have"
                        }}
                        participated
                    </div>
                </Link>

                <Link
                    :href="urls.admin.results_index"
                    class="kpi-card"
                    title="View participation analytics"
                    :aria-label="`Participation rate: ${participationRate}%. ${totalVotesCast} ballots cast.`"
                >
                    <div class="kpi-card__icon" aria-hidden="true">
                        <BarChart3 :size="24" />
                    </div>
                    <div class="kpi-card__value">{{ participationRate }}%</div>
                    <div class="kpi-card__label">Participation Rate</div>
                    <div class="kpi-card__detail">
                        {{ totalVotesCast }}
                        {{ totalVotesCast === 1 ? "ballot" : "ballots" }} cast
                    </div>
                    <div class="kpi-card__progress" role="presentation">
                        <div
                            class="kpi-card__progress-fill"
                            :style="{
                                width: Math.min(100, participationRate) + '%',
                            }"
                        ></div>
                    </div>
                </Link>

                <Link
                    :href="urls.admin.elections_index"
                    class="kpi-card"
                    title="Positions and candidates in active elections"
                    :aria-label="`Positions: ${totalPositions}, candidates: ${totalNominees}.`"
                >
                    <div class="kpi-card__icon" aria-hidden="true">
                        <Grid3x3 :size="24" />
                    </div>
                    <div class="kpi-card__value">
                        {{ totalPositions }}/{{ totalNominees }}
                    </div>
                    <div class="kpi-card__label">Positions / Candidates</div>
                    <div class="kpi-card__detail">Across active elections</div>
                </Link>
            </section>

            <!-- Quick Actions -->
            <section
                class="card quick-actions-card"
                aria-labelledby="quick-actions-heading"
            >
                <h2 id="quick-actions-heading" class="quick-actions-heading">
                    Quick actions
                </h2>
                <div class="quick-actions-wrap">
                    <Link
                        :href="
                        urls.admin?.elections_create ||
                        '/admin/elections/create'
                    "
                        class="quick-action-btn"
                        >Create election</Link
                    >
                    <Link
                        :href="urls.admin?.elections_index || '/admin/elections'"
                        class="quick-action-btn"
                        >Manage elections</Link
                    >
                    <Link
                        :href="urls.admin?.voters_index || '/admin/voters'"
                        class="quick-action-btn"
                        >Manage voters</Link
                    >
                    <Link
                        :href="urls.admin?.announcements_index ||'/admin/announcements'"
                        class="quick-action-btn"
                        >Announcements</Link
                    >
                </div>
            </section>

            <!-- Active Elections -->
            <section
                v-if="activeElections.length > 0"
                class="card"
                aria-labelledby="active-elections-heading"
            >
                <h2 id="active-elections-heading" class="card-title">
                    Active elections – live overview
                </h2>

                <div
                    v-for="election in activeElections"
                    :key="election.id"
                    class="active-election-block"
                >
                    <div class="active-election-header">
                        <div>
                            <h3 class="active-election-title">
                                {{ election.title }}
                            </h3>
                            <p class="active-election-meta">
                                Created {{ formatDate(election.created_at) }}
                            </p>
                        </div>
                        <Link
                            :href="election.edit_url"
                            class="btn btn-secondary btn-sm"
                            >Edit election</Link
                        >
                    </div>

                    <div class="election-stats-grid">
                        <div class="election-stat-box">
                            <p class="election-stat-label">Votes cast</p>
                            <p class="election-stat-value">
                                {{ election.votes_count }}
                            </p>
                        </div>
                        <div class="election-stat-box">
                            <p class="election-stat-label">Participation</p>
                            <p class="election-stat-value">
                                {{ election.participation_rate }}%
                            </p>
                        </div>
                        <div class="election-stat-box">
                            <p class="election-stat-label">Positions</p>
                            <p class="election-stat-value">
                                {{ election.positions_count }}
                            </p>
                        </div>
                    </div>

                    <!-- Results by Position -->
                    <template v-if="getElectionResults(election.id)">
                        <div
                            v-for="(
                                candidates, positionName
                            ) in getElectionResults(election.id)"
                            :key="positionName"
                            class="result-chart"
                        >
                            <h4 class="result-position-title">
                                {{ positionName }}
                            </h4>
                            <div
                                v-for="(candidate, idx) in candidates"
                                :key="idx"
                                class="result-candidate-row"
                            >
                                <div class="result-candidate-header">
                                    <span class="result-candidate-name">
                                        {{ idx + 1 }}. {{ candidate.name }}
                                    </span>
                                    <span class="result-candidate-badge">
                                        {{ candidate.votes }} votes ({{
                                            candidate.percentage
                                        }}%)
                                    </span>
                                </div>
                                <div class="result-progress-track">
                                    <div
                                        class="result-progress-fill"
                                        :style="{
                                            width: candidate.percentage + '%',
                                        }"
                                    >
                                        <span
                                            v-if="candidate.percentage > 10"
                                            class="result-progress-text"
                                        >
                                            {{ candidate.percentage }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </section>

            <section v-else class="card" aria-label="No active elections">
                <div class="empty-state">
                    <p class="empty-state-title">No active elections</p>
                    <p class="empty-state-text">
                        Create a new election to begin collecting votes.
                    </p>
                    <Link
                        :href="urls.admin.elections_create"
                        class="btn btn-success"
                        >Create election</Link
                    >
                </div>
            </section>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import { FileText, Users, BarChart3, Grid3x3, Plus, Edit, Trash2, UserCheck, AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    activeElections: { type: Array, default: () => [] },
    allElections: { type: Array, default: () => [] },
    totalElections: { type: Number, default: 0 },
    activeElectionsCount: { type: Number, default: 0 },
    totalVoters: { type: Number, default: 0 },
    totalVotesCast: { type: Number, default: 0 },
    totalPositions: { type: Number, default: 0 },
    totalNominees: { type: Number, default: 0 },
    totalVotedCount: { type: Number, default: 0 },
    participationRate: { type: Number, default: 0 },
    results: { type: Object, default: () => ({}) },
});

const page = usePage();
const urls = page.props.urls || { admin: {} };

function formatDate(val) {
    if (!val) return "";
    const d = new Date(val);
    return d.toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "2-digit",
    });
}

function getElectionResults(electionId) {
    return props.results[electionId] || null;
}
</script>

<style scoped>
.quick-actions-card {
    margin-bottom: 30px;
}
.quick-actions-heading {
    margin-bottom: 20px;
    color: var(--primary);
    font-size: 1.25rem;
}
.quick-actions-wrap {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.quick-action-btn {
    display: inline-block;
    padding: 10px 20px;
    margin: 8px 0;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    border-radius: 999px;
    text-decoration: none;
    transition: all 0.2s ease;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
}
.quick-action-btn:hover {
    background: linear-gradient(135deg, #059669, #10b981);
    box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
    transform: translateY(-1px);
}
.result-chart {
    margin-bottom: 30px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}
.active-election-block {
    margin-bottom: 40px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 12px;
    border-left: 4px solid #059669;
}
.active-election-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.active-election-title {
    font-size: 20px;
    margin: 0;
    color: #047857;
}
.active-election-meta {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 14px;
}
.election-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}
.election-stat-box {
    background: white;
    padding: 15px;
    border-radius: 8px;
}
.election-stat-label {
    margin: 0;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.election-stat-value {
    margin: 8px 0 0;
    font-size: 24px;
    font-weight: 700;
    color: #047857;
}
.result-position-title {
    margin-top: 0;
    color: #0f172a;
}
.result-candidate-row {
    margin-bottom: 15px;
}
.result-candidate-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    align-items: center;
}
.result-candidate-name {
    font-weight: 600;
    color: #111827;
}
.result-candidate-badge {
    background: #059669;
    color: white;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}
.result-progress-track {
    background: #e5e7eb;
    height: 20px;
    border-radius: 999px;
    overflow: hidden;
}
.result-progress-fill {
    width: 0;
    background: linear-gradient(90deg, #036b30, #06ae57);
    height: 100%;
    display: flex;
    align-items: center;
    padding: 0 10px;
    transition: width 0.4s ease;
}
.result-progress-text {
    color: #ecfdf3;
    font-weight: 600;
    font-size: 12px;
}
.btn-sm {
    padding: 8px 16px;
}
</style>
