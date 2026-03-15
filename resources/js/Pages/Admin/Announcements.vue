<template>
  <DashboardLayout page-title="Announcements" page-subtitle="Create and manage campus-wide announcements.">
    <div class="announcements-header">
      <h1>📢 Announcements</h1>
      <div class="announcements-header-action">
        <button type="button" class="btn btn-light" style="color: #047857; font-weight: 700" @click="showCreateModal = true">
          ➕ New Announcement
        </button>
      </div>
    </div>

    <div class="announcements-container">
      <div v-for="announcement in announcements" :key="announcement.id" class="announcement-item">
        <div class="announcement-header">
          <div style="flex: 1">
            <div class="announcement-content">{{ announcement.content }}</div>
            <div class="announcement-meta">
              <div class="announcement-meta-item">📅 Posted: {{ formatDate(announcement.created_at) }}</div>
              <div v-if="announcement.updated_at !== announcement.created_at" class="announcement-meta-item edited">
                ✏️ Edited: {{ formatDate(announcement.updated_at) }}
              </div>
            </div>
          </div>
        </div>
        <div class="announcement-actions">
          <button type="button" class="btn btn-primary btn-sm" @click="openEdit(announcement)">✏️ Edit</button>
          <button type="button" class="btn btn-danger btn-sm" @click="confirmDelete(announcement.destroy_url)">🗑️ Delete</button>
        </div>
      </div>
      <div v-if="!announcements.length" class="empty-announcements">
        <div class="empty-icon">📢</div>
        <div class="empty-title">No Announcements Yet</div>
        <div class="empty-text">Create your first announcement to communicate with voters.</div>
        <button type="button" class="btn btn-primary" @click="showCreateModal = true">➕ Create Announcement</button>
      </div>
    </div>

    <!-- Create Modal -->
    <div v-show="showCreateModal" class="modal-overlay" @click.self="showCreateModal = false">
      <div class="modal">
        <h2 class="modal-title">Create New Announcement</h2>
        <form @submit.prevent="createForm.post(urls.admin.announcements_store)">
          <div class="modal-form-group">
            <textarea v-model="createForm.content" placeholder="Write your announcement here..." required rows="6"></textarea>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" @click="showCreateModal = false">Cancel</button>
            <button type="submit" class="btn btn-primary" :disabled="createForm.processing">Create Announcement</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-show="showEditModal" class="modal-overlay" @click.self="showEditModal = false">
      <div class="modal">
        <h2 class="modal-title">Edit Announcement</h2>
        <form @submit.prevent="editForm.put(editingAnnouncement.update_url)">
          <div class="modal-form-group">
            <textarea v-model="editForm.content" placeholder="Write your announcement here..." required rows="6"></textarea>
          </div>
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" @click="showEditModal = false">Cancel</button>
            <button type="submit" class="btn btn-primary" :disabled="editForm.processing">Update Announcement</button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { useForm, usePage, router } from '@inertiajs/inertia-vue3';
import { ref, watch } from 'vue';
import DashboardLayout from '../../Layouts/DashboardLayout.vue';

const props = defineProps({
  announcements: { type: Array, default: () => [] },
});

const page = usePage();
const urls = page.props.urls || { admin: {} };

const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingAnnouncement = ref(null);

const createForm = useForm({ content: '' });
const editForm = useForm({ content: '' });

watch(showCreateModal, (v) => { if (!v) createForm.reset(); });
watch(showEditModal, (v) => { if (!v) editingAnnouncement.value = null; });

function formatDate(val) {
  if (!val) return '';
  const d = new Date(val);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' });
}

function openEdit(announcement) {
  editingAnnouncement.value = announcement;
  editForm.content = announcement.content;
  showEditModal.value = true;
}

function confirmDelete(destroyUrl) {
  if (confirm('Delete this announcement?')) {
    router.delete(destroyUrl);
  }
}
</script>

<style scoped>
.announcements-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; padding: 40px; border-radius: 16px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
.announcements-header h1 { font-size: 28px; font-weight: 700; margin: 0; }
.announcements-container { background: white; border-radius: 16px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; }
.announcement-item { padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 16px; }
.announcement-content { font-size: 16px; color: #1f2937; font-weight: 500; line-height: 1.6; margin-bottom: 12px; }
.announcement-meta { display: flex; gap: 25px; font-size: 13px; }
.announcement-meta-item { color: #6b7280; }
.announcement-meta-item.edited { color: #d97706; font-style: italic; }
.announcement-actions { display: flex; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
.empty-announcements { text-align: center; padding: 60px 40px; }
.empty-icon { font-size: 64px; margin-bottom: 20px; opacity: 0.5; }
.empty-title { font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 10px; }
.empty-text { font-size: 16px; color: #6b7280; margin-bottom: 30px; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px; }
.modal { background: white; border-radius: 16px; padding: 30px; max-width: 500px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
.modal-title { font-size: 24px; font-weight: 700; color: #1f2937; margin-bottom: 20px; }
.modal-form-group { margin-bottom: 20px; }
.modal-form-group textarea { width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; font-family: inherit; }
.modal-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 25px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
</style>
