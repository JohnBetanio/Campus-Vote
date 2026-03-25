<template>
    <DashboardLayout
        page-title="Announcements"
        page-subtitle="Create and manage campus-wide announcements."
    >
        <div class="announcements-header">
            <h1>
                <Bell class="inline-block mr-2" :size="24" />
                Announcements
            </h1>
            <div class="announcements-header-action">
                <button
                    type="button"
                    class="btn btn-light"
                    style="color: white; font-weight: 700"
                    @click="showCreateModal = true"
                >
                    <Plus class="inline-block mr-2" :size="16" />
                    New Announcement
                </button>
            </div>
        </div>

        <div class="announcements-container">
            <div
                v-for="announcement in announcements"
                :key="announcement.id"
                class="announcement-item"
            >
                <div class="announcement-header">
                    <div style="flex: 1">
                        <div class="announcement-content">
                            {{ announcement.content }}
                        </div>
                        <div class="announcement-meta">
                            <div class="announcement-meta-item">
                                <Clock class="inline-block mr-2" :size="14" />
                                Posted:
                                {{ formatDate(announcement.created_at) }}
                            </div>
                            <div
                                v-if="
                                    announcement.updated_at !==
                                    announcement.created_at
                                "
                                class="announcement-meta-item edited"
                            >
                                Edited:
                                {{ formatDate(announcement.updated_at) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="announcement-actions">
                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        @click="openEdit(announcement)"
                    >
                        <Edit class="inline-block mr-2" :size="14" />
                        Edit
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        @click="openDeleteConfirm(announcement)"
                    >
                        <Trash2 class="inline-block mr-2" :size="14" />
                        Delete
                    </button>
                </div>
            </div>
            <div v-if="!announcements.length" class="empty-announcements">
                <div class="empty-icon">
                    <Bell :size="48" />
                </div>
                <div class="empty-title">No Announcements Yet</div>
                <div class="empty-text">
                    Create your first announcement to communicate with voters.
                </div>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="showCreateModal = true"
                >
                    <Plus class="inline-block mr-2" :size="16" />
                    Create Announcement
                </button>
            </div>
        </div>

        <!-- Create Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-show="showCreateModal"
                    class="modal-overlay-pro"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="create-modal-title"
                    @click.self="closeCreateModal"
                >
                    <div class="modal-card-pro">
                        <h2 id="create-modal-title" class="modal-title-pro">
                            Create New Announcement
                        </h2>
                        <form
                            @submit.prevent="
                                createForm.post(
                                    adminPath(
                                        'announcements_store',
                                        '/admin/announcements',
                                    ),
                                )
                            "
                        >
                            <div class="modal-form-group">
                                <textarea
                                    v-model="createForm.content"
                                    placeholder="Write your announcement here..."
                                    required
                                    rows="6"
                                ></textarea>
                            </div>
                            <div class="modal-actions">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="closeCreateModal"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="createForm.processing"
                                >
                                    Create Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Edit Modal -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-show="showEditModal"
                    class="modal-overlay-pro"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="edit-modal-title"
                    @click.self="requestCloseEdit"
                >
                    <div class="modal-card-pro">
                        <h2 id="edit-modal-title" class="modal-title-pro">
                            Edit Announcement
                        </h2>
                        <form
                            @submit.prevent="
                                editForm.put(editingAnnouncement.update_url)
                            "
                        >
                            <div class="modal-form-group">
                                <textarea
                                    v-model="editForm.content"
                                    placeholder="Write your announcement here..."
                                    required
                                    rows="6"
                                ></textarea>
                            </div>
                            <div class="modal-actions">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="requestCloseEdit"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="editForm.processing"
                                >
                                    Update Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Discard unsaved edit -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="showDiscardEditModal"
                    class="confirm-modal-overlay"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="discard-edit-title"
                    @click.self="showDiscardEditModal = false"
                >
                    <div class="confirm-modal-card">
                        <div class="confirm-modal-icon confirm-modal-icon--warn">
                            <AlertTriangle :size="28" />
                        </div>
                        <h3 id="discard-edit-title" class="confirm-modal-title">
                            Discard changes?
                        </h3>
                        <p class="confirm-modal-text">
                            You have unsaved edits to this announcement. If you
                            close now, those changes will be lost.
                        </p>
                        <div class="confirm-modal-actions">
                            <button
                                type="button"
                                class="confirm-btn confirm-btn--cancel"
                                @click="showDiscardEditModal = false"
                            >
                                Keep editing
                            </button>
                            <button
                                type="button"
                                class="confirm-btn confirm-btn--delete"
                                @click="confirmDiscardEdit"
                            >
                                Discard
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Delete confirmation -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div
                    v-if="deleteModal"
                    class="confirm-modal-overlay"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="delete-announcement-title"
                    @click.self="closeDeleteModal"
                >
                    <div class="confirm-modal-card">
                        <div class="confirm-modal-icon confirm-modal-icon--delete">
                            <Trash2 :size="28" />
                        </div>
                        <h3
                            id="delete-announcement-title"
                            class="confirm-modal-title"
                        >
                            Delete this announcement?
                        </h3>
                        <p class="confirm-modal-preview">
                            {{ deleteModal.preview }}
                        </p>
                        <p class="confirm-modal-text">
                            Voters will no longer see this message. This action
                            cannot be undone.
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
import { useForm, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { ref, watch, onMounted, onUnmounted } from "vue";
import DashboardLayout from "../../Layouts/DashboardLayout.vue";
import {
    Bell,
    Plus,
    Edit,
    Trash2,
    Clock,
    AlertTriangle,
} from "lucide-vue-next";

defineProps({
    announcements: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { admin: {} };

const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDiscardEditModal = ref(false);
const editingAnnouncement = ref(null);
const editOriginalContent = ref("");
const deleteModal = ref(null);
const deleteProcessing = ref(false);

const createForm = useForm({ content: "" });
const editForm = useForm({ content: "" });

function adminPath(key, fallback) {
    const u = urls.admin?.[key];
    return u && u !== "#" ? u : fallback;
}

function truncatePreview(text, max = 120) {
    if (!text) return "";
    const t = text.trim();
    return t.length > max ? t.slice(0, max).trim() + "…" : t;
}

watch(showCreateModal, (v) => {
    if (!v) createForm.reset();
});

watch(showEditModal, (v) => {
    if (!v) {
        editingAnnouncement.value = null;
        editOriginalContent.value = "";
    }
});

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

function closeCreateModal() {
    showCreateModal.value = false;
}

function openEdit(announcement) {
    editingAnnouncement.value = announcement;
    editForm.content = announcement.content;
    editOriginalContent.value = announcement.content;
    showEditModal.value = true;
}

function requestCloseEdit() {
    if (editForm.content !== editOriginalContent.value) {
        showDiscardEditModal.value = true;
        return;
    }
    showEditModal.value = false;
}

function confirmDiscardEdit() {
    showDiscardEditModal.value = false;
    showEditModal.value = false;
    editForm.reset();
    editingAnnouncement.value = null;
    editOriginalContent.value = "";
}

function openDeleteConfirm(announcement) {
    deleteModal.value = {
        destroy_url: announcement.destroy_url,
        preview: truncatePreview(announcement.content),
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
    if (e.key !== "Escape") return;
    if (deleteModal.value) closeDeleteModal();
    else if (showDiscardEditModal.value) showDiscardEditModal.value = false;
    else if (showEditModal.value) requestCloseEdit();
    else if (showCreateModal.value) closeCreateModal();
}

onMounted(() => window.addEventListener("keydown", onKeydown));
onUnmounted(() => window.removeEventListener("keydown", onKeydown));
</script>

<style scoped>
.announcements-header {
    background: linear-gradient(
        135deg,
        var(--primary) 0%,
        var(--primary-light) 100%
    );
    color: white;
    padding: 40px;
    border-radius: 16px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.announcements-header-action {
    background-color: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
}
.announcements-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
}
.announcements-container {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
}
.announcement-item {
    padding: 24px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 16px;
}
.announcement-content {
    font-size: 16px;
    color: #1f2937;
    font-weight: 500;
    line-height: 1.6;
    margin-bottom: 12px;
}
.announcement-meta {
    display: flex;
    gap: 25px;
    font-size: 13px;
    flex-wrap: wrap;
}
.announcement-meta-item {
    color: #6b7280;
}
.announcement-meta-item.edited {
    color: #d97706;
    font-style: italic;
}
.announcement-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}
.empty-announcements {
    text-align: center;
    padding: 60px 40px;
}
.empty-icon {
    margin-bottom: 20px;
    opacity: 0.5;
    display: flex;
    justify-content: center;
}
.empty-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 10px;
}
.empty-text {
    font-size: 16px;
    color: #6b7280;
    margin-bottom: 30px;
}

.modal-overlay-pro {
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
}
.modal-card-pro {
    background: white;
    border-radius: 16px;
    padding: 30px;
    max-width: 500px;
    width: 100%;
    box-shadow:
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(226, 232, 240, 0.9);
}
.modal-title-pro {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 20px;
    letter-spacing: -0.02em;
}
.modal-form-group {
    margin-bottom: 20px;
}
.modal-form-group textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 15px;
    font-family: inherit;
    resize: vertical;
    min-height: 120px;
}
.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
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
.confirm-modal-icon--warn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.35);
}
.confirm-modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 12px;
    letter-spacing: -0.02em;
}
.confirm-modal-preview {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.5;
    margin: 0 0 12px;
    text-align: left;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
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
    min-width: 120px;
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
.modal-fade-enter-active .modal-card-pro,
.modal-fade-leave-active .modal-card-pro,
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
.modal-fade-enter-from .modal-card-pro,
.modal-fade-leave-to .modal-card-pro,
.modal-fade-enter-from .confirm-modal-card,
.modal-fade-leave-to .confirm-modal-card {
    transform: scale(0.96) translateY(8px);
    opacity: 0;
}
</style>
