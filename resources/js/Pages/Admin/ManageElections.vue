<template>
    <DashboardLayout
        page-title="Manage Elections"
        page-subtitle="Create, edit, and monitor all elections in the system."
    >
        <template #actions>
            <Link :href="urls.admin?.elections_create || '/admin/elections/create'" class="btn btn-primary"
                >+ Create New Election</Link
            >
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
                        <small class="election-meta-text"
                            >Created
                            {{ formatDate(election.created_at) }}</small
                        >
                    </div>
                    <div>
                        <span class="status-badge" :class="election.status">
                            <span>●</span>
                            {{
                                election.status === "active"
                                    ? "Active"
                                    : "Ended"
                            }}
                        </span>
                    </div>
                </div>

                <div class="election-meta">
                    <div class="meta-item">
                        <span class="meta-value">{{
                            election.positions_count
                        }}</span>
                        <span class="meta-label">Positions</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-value">{{
                            election.candidates_count
                        }}</span>
                        <span class="meta-label">Candidates</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-value">{{
                            election.votes_cast
                        }}</span>
                        <span class="meta-label">Votes Cast</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-value"
                            >{{ election.participation_rate }}%</span
                        >
                        <span class="meta-label">Participation</span>
                    </div>
                </div>

                <div
                    v-if="election.positions && election.positions.length > 0"
                    class="positions-section"
                >
                    <h3 class="section-title">
                        <Shield class="section-icon" :size="20" />
                        Positions & Candidates
                    </h3>
                    <div class="position-grid">
                        <div
                            v-for="position in election.positions"
                            :key="position.id"
                            class="position-card"
                            :class="{ ended: election.status === 'ended' }"
                        >
                            <div class="position-header">
                                <div class="position-icon">
                                    <Users :size="20" />
                                </div>
                                <div class="position-name">
                                    {{ position.name }}
                                </div>
                            </div>
                            <div class="candidate-list">
                                <div
                                    v-for="candidate in position.candidates"
                                    :key="candidate.id"
                                    class="candidate-item"
                                >
                                    <div class="candidate-avatar">
                                        {{
                                            candidate.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}
                                    </div>
                                    <div class="candidate-info">
                                        <div class="candidate-name">
                                            {{ candidate.name }}
                                        </div>
                                        <div class="candidate-badge">
                                            Candidate
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="
                                        !position.candidates ||
                                        position.candidates.length === 0
                                    "
                                    class="no-candidates"
                                >
                                    <AlertCircle :size="16" />
                                    No candidates added
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="action-group">
                    <Link
                        :href="election.edit_url"
                        class="action-btn action-btn-edit"
                    >
                        <Edit class="action-icon" :size="16" />
                        Edit Election
                    </Link>
                    <button
                        v-if="election.status === 'active'"
                        type="button"
                        class="action-btn action-btn-end"
                        @click="openEndConfirm(election)"
                    >
                        <Shield class="action-icon" :size="16" />
                        End Election
                    </button>
                    <button
                        type="button"
                        class="action-btn action-btn-delete"
                        @click="openDeleteConfirm(election)"
                    >
                        <Trash2 class="action-icon" :size="16" />
                        Delete Election
                    </button>
                </div>
            </div>

            <!-- Confirmation modal (replaces browser alert) -->
            <Teleport to="body">
                <Transition name="modal-fade">
                    <div
                        v-if="confirmModal"
                        class="confirm-modal-overlay"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="confirm-modal-title"
                        @click.self="closeConfirmModal"
                    >
                        <div class="confirm-modal-card">
                            <div
                                class="confirm-modal-icon"
                                :class="
                                    confirmModal.type === 'end'
                                        ? 'confirm-modal-icon--end'
                                        : 'confirm-modal-icon--delete'
                                "
                            >
                                <Shield
                                    v-if="confirmModal.type === 'end'"
                                    :size="28"
                                />
                                <Trash2 v-else :size="28" />
                            </div>
                            <h3 id="confirm-modal-title" class="confirm-modal-title">
                                {{
                                    confirmModal.type === "end"
                                        ? "End this election?"
                                        : "Delete this election?"
                                }}
                            </h3>
                            <p class="confirm-modal-election-name">
                                {{ confirmModal.title }}
                            </p>
                            <p class="confirm-modal-text">
                                {{
                                    confirmModal.type === "end"
                                        ? "Voting will close and winners will be calculated. This cannot be reversed from the voter side."
                                        : "All positions, candidates, and vote records tied to this election will be permanently removed."
                                }}
                            </p>
                            <div class="confirm-modal-actions">
                                <button
                                    type="button"
                                    class="confirm-btn confirm-btn--cancel"
                                    @click="closeConfirmModal"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    class="confirm-btn"
                                    :class="
                                        confirmModal.type === 'end'
                                            ? 'confirm-btn--end'
                                            : 'confirm-btn--delete'
                                    "
                                    :disabled="confirmProcessing"
                                    @click="executeConfirm"
                                >
                                    {{
                                        confirmModal.type === "end"
                                            ? "End election"
                                            : "Delete permanently"
                                    }}
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <div v-if="elections.length === 0" class="card">
                <div class="empty-elections-state">
                        <FileText class="empty-elections-icon" :size="64" />
                        <h3 class="empty-elections-title">No Elections Created</h3>
                    <p class="empty-elections-text">
                        Start by creating your first election to begin the
                        voting process.
                    </p>
                    <Link
                        :href="urls.admin?.elections_create || '/admin/elections/create'"
                        class="btn btn-primary"
                    >
                        <Plus class="inline-block mr-2" :size="16" />
                        Create Election
                    </Link>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import DashboardLayout from "../../Layouts/DashboardLayout.vue";
import { Shield, Users, Trash2, Edit, AlertCircle, FileText, Plus } from 'lucide-vue-next';

const props = defineProps({
    elections: { type: Array, default: () => [] },
    activeElections: { type: Array, default: () => [] },
    endedElections: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { admin: {} };

/** @type {import('vue').Ref<{ type: 'end' | 'delete'; url: string; title: string } | null>} */
const confirmModal = ref(null);
const confirmProcessing = ref(false);

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

function openEndConfirm(election) {
    confirmModal.value = {
        type: "end",
        url: election.end_url,
        title: election.title,
    };
}

function openDeleteConfirm(election) {
    confirmModal.value = {
        type: "delete",
        url: election.destroy_url,
        title: election.title,
    };
}

function closeConfirmModal() {
    if (confirmProcessing.value) return;
    confirmModal.value = null;
}

function onKeydown(e) {
    if (e.key === "Escape" && confirmModal.value) closeConfirmModal();
}

function executeConfirm() {
    if (!confirmModal.value) return;
    const { type, url } = confirmModal.value;
    confirmProcessing.value = true;
    const done = () => {
        confirmProcessing.value = false;
        confirmModal.value = null;
    };
    if (type === "end") {
        Inertia.post(url, {}, { onFinish: done, onError: done });
    } else {
        Inertia.delete(url, { onFinish: done, onError: done });
    }
}

onMounted(() => window.addEventListener("keydown", onKeydown));
onUnmounted(() => window.removeEventListener("keydown", onKeydown));
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

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.3px;
}

.section-icon {
    color: #059669;
}

.position-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

.position-card {
    background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.98) 0%,
        rgba(248, 250, 252, 0.95) 100%
    );
    backdrop-filter: blur(20px);
    padding: 20px;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.5);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
}

.position-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #059669 0%, #10b981 50%, #06b6d4 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.position-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 40px rgba(5, 150, 105, 0.15);
    border-color: rgba(5, 150, 105, 0.3);
}

.position-card:hover::before {
    transform: scaleX(1);
}

.position-card.ended {
    opacity: 0.7;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-color: #cbd5e1;
}

.position-card.ended::before {
    background: linear-gradient(90deg, #64748b 0%, #94a3b8 100%);
}

.position-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.position-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    flex-shrink: 0;
}

.position-card.ended .position-icon {
    background: linear-gradient(135deg, #64748b 0%, #94a3b8 100%);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.position-name {
    font-weight: 700;
    color: #1e293b;
    font-size: 16px;
    letter-spacing: -0.3px;
    line-height: 1.3;
}

.position-card.ended .position-name {
    color: #64748b;
}

.candidate-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.candidate-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 10px;
    border: 1px solid rgba(226, 232, 240, 0.5);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.candidate-item::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.candidate-item:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-color: rgba(5, 150, 105, 0.3);
}

.candidate-item:hover::before {
    transform: scaleX(1);
}

.candidate-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
    border: 2px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.candidate-info {
    flex: 1;
}

.candidate-name {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    line-height: 1.4;
}

.candidate-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: #059669;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid rgba(5, 150, 105, 0.2);
}

.position-card.ended .candidate-badge {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    color: #64748b;
    border-color: rgba(100, 116, 139, 0.2);
}

.no-candidates {
    text-align: center;
    padding: 20px;
    color: #94a3b8;
    font-style: italic;
    font-size: 13px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 10px;
    border: 1px dashed #cbd5e1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.action-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid rgba(226, 232, 240, 0.5);
}

.action-form {
    display: inline;
}

.action-btn {
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
    min-width: 120px;
    justify-content: center;
}

.action-btn::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    transition: left 0.6s ease;
}

.action-btn:hover::before {
    left: 100%;
}

.action-btn-edit {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.action-btn-edit:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
}

.action-btn-end {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.action-btn-end:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
}

.action-btn-delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.action-btn-delete:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
}

.action-icon {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
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

/* Modal (teleported to body — keep classes unique for clarity) */
.confirm-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
}

.confirm-modal-card {
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: 16px;
    padding: 28px 28px 24px;
    box-shadow:
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(226, 232, 240, 0.9);
    text-align: center;
}

.confirm-modal-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 16px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.confirm-modal-icon--end {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.35);
}

.confirm-modal-icon--delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
}

.confirm-modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 8px;
    letter-spacing: -0.02em;
}

.confirm-modal-election-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #059669;
    margin: 0 0 12px;
    line-height: 1.4;
}

.confirm-modal-text {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.55;
    margin: 0 0 24px;
}

.confirm-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.confirm-btn {
    min-width: 132px;
    padding: 11px 18px;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease,
        opacity 0.15s ease;
}

.confirm-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}

.confirm-btn--cancel {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.confirm-btn--cancel:hover:not(:disabled) {
    background: #e2e8f0;
}

.confirm-btn--end {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
}

.confirm-btn--end:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
}

.confirm-btn--delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
}

.confirm-btn--delete:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(239, 68, 68, 0.45);
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}

.modal-fade-enter-active .confirm-modal-card,
.modal-fade-leave-active .confirm-modal-card {
    transition:
        transform 0.2s ease,
        opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

.modal-fade-enter-from .confirm-modal-card,
.modal-fade-leave-to .confirm-modal-card {
    transform: scale(0.96) translateY(8px);
    opacity: 0;
}
</style>
