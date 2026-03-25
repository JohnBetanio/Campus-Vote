<template>
    <DashboardLayout
        page-title="Voters Management"
        page-subtitle="View and manage registered voters."
    >
        <div class="voters-header">
            <div class="voters-header-content">
                <h1>Registered Voters</h1>
                <p>Manage and monitor all registered voters in the system</p>
                <div class="voters-stats">
                    <div class="stat-box">
                        <div class="stat-box-label">Total Voters</div>
                        <div class="stat-box-value">{{ voters.length }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="voters-container">
            <div v-for="voter in voters" :key="voter.id" class="voter-row">
                <div class="voter-info">
                    <div class="voter-name">{{ voter.name }}</div>
                    <div class="voter-email">{{ voter.email }}</div>
                    <div class="voter-course">
                        Course: {{ voter.course || "Undecided" }}
                    </div>
                </div>
                <div class="voter-actions">
                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="openDeleteConfirm(voter)"
                    >
                        <Trash2 class="inline-block mr-2" :size="14" />
                        Delete
                    </button>
                </div>
            </div>
            <div v-if="!voters.length" class="voters-empty">
                <div class="voters-empty-icon">
                    <Users :size="56" />
                </div>
                <div class="voters-empty-title">No Voters Yet</div>
                <div class="voters-empty-text">
                    No voters have been registered in the system yet.
                </div>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="deleteModal"
                    class="confirm-modal-overlay"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="delete-voter-title"
                    @click.self="closeDeleteModal"
                >
                    <div class="confirm-modal-card">
                        <div class="confirm-modal-icon confirm-modal-icon--delete">
                            <Trash2 :size="28" />
                        </div>
                        <h3 id="delete-voter-title" class="confirm-modal-title">
                            Delete this voter?
                        </h3>
                        <p class="confirm-modal-preview">
                            <strong>{{ deleteModal.name }}</strong><br />
                            <span class="confirm-modal-email">{{
                                deleteModal.email
                            }}</span>
                        </p>
                        <p class="confirm-modal-text">
                            This removes the voter account. Related vote records
                            are removed per database rules. This cannot be undone.
                        </p>
                        <div class="confirm-modal-actions">
                            <button
                                type="button"
                                class="confirm-btn confirm-btn--cancel"
                                @click="closeDeleteModal"
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                class="confirm-btn confirm-btn--delete"
                                :disabled="deleteProcessing"
                                @click="executeDelete"
                            >
                                Delete permanently
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import DashboardLayout from "../../Layouts/DashboardLayout.vue";
import { Trash2, Users } from "lucide-vue-next";

defineProps({
    voters: { type: Array, default: () => [] },
});

/** @type {import('vue').Ref<{ destroy_url: string; name: string; email: string } | null>} */
const deleteModal = ref(null);
const deleteProcessing = ref(false);

function openDeleteConfirm(voter) {
    deleteModal.value = {
        destroy_url: voter.destroy_url,
        name: voter.name,
        email: voter.email,
    };
}

function closeDeleteModal() {
    if (deleteProcessing.value) return;
    deleteModal.value = null;
}

function executeDelete() {
    if (!deleteModal.value) return;
    deleteProcessing.value = true;
    const url = deleteModal.value.destroy_url;
    const done = () => {
        deleteProcessing.value = false;
        deleteModal.value = null;
    };
    Inertia.delete(url, { onFinish: done, onError: done });
}

function onKeydown(e) {
    if (e.key === "Escape" && deleteModal.value) closeDeleteModal();
}

onMounted(() => window.addEventListener("keydown", onKeydown));
onUnmounted(() => window.removeEventListener("keydown", onKeydown));
</script>

<style scoped>
.voters-header {
    background: linear-gradient(
        135deg,
        var(--primary) 0%,
        var(--primary-light) 100%
    );
    color: white;
    padding: 40px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25);
}
.voters-header-content {
    position: relative;
    z-index: 1;
}
.voters-header h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 15px 0;
}
.voters-header p {
    font-size: 16px;
    opacity: 0.95;
    color: white;
    margin: 0;
}
.voters-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.stat-box {
    background: rgba(255, 255, 255, 0.15);
    padding: 15px 20px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.stat-box-label {
    font-size: 12px;
    opacity: 0.85;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-box-value {
    font-size: 24px;
    font-weight: 700;
    margin-top: 8px;
}
.voters-container {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}
.voter-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
    gap: 16px;
    flex-wrap: wrap;
}
.voter-row:last-child {
    border-bottom: none;
}
.voter-name {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}
.voter-email {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 4px;
}
.voter-course {
    font-size: 13px;
    color: #9ca3af;
    font-weight: 500;
}
.voter-actions {
    flex-shrink: 0;
}
.voters-empty {
    text-align: center;
    padding: 60px 40px;
}
.voters-empty-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    color: #94a3b8;
    opacity: 0.85;
}
.voters-empty-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}
.voters-empty-text {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 30px;
}

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
.confirm-modal-icon--delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
}
.confirm-modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 12px;
    letter-spacing: -0.02em;
}
.confirm-modal-preview {
    font-size: 0.95rem;
    color: #334155;
    line-height: 1.5;
    margin: 0 0 12px;
    text-align: left;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}
.confirm-modal-email {
    font-size: 0.875rem;
    color: #64748b;
}
.confirm-modal-text {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.55;
    margin: 0 0 24px;
    text-align: left;
}
.confirm-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.confirm-btn {
    min-width: 120px;
    padding: 11px 18px;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition:
        transform 0.15s ease,
        opacity 0.15s ease;
}
.confirm-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
.confirm-btn--cancel {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.confirm-btn--cancel:hover:not(:disabled) {
    background: #e2e8f0;
}
.confirm-btn--delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
}
.confirm-btn--delete:hover:not(:disabled) {
    transform: translateY(-1px);
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
