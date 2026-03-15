@extends('layouts.dashboard')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('page-subtitle', 'Create and manage campus-wide announcements.')

@section('content')
    <style>
        .announcements-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(5, 150, 105, 0.25);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        .announcements-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .announcements-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .announcements-header-action {
            position: relative;
            z-index: 1;
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
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .announcement-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #047857 0%, #059669 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .announcement-item:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-color: #059669;
        }

        .announcement-item:hover::before {
            opacity: 1;
        }

        .announcement-item:last-child {
            margin-bottom: 0;
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 12px;
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
        }

        .announcement-meta-item {
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
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
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
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

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        .modal {
            background: white;
            border-radius: 16px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
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
            transition: all 0.3s ease;
        }

        .modal-form-group textarea:focus {
            outline: none;
            border-color: #047857;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>

    <div class="announcements-header">
        <h1>📢 Announcements</h1>
        <div class="announcements-header-action">
            <button class="btn btn-light" onclick="openCreateModal()" style="color: #047857; font-weight: 700;">
                ➕ New Announcement
            </button>
        </div>
    </div>

    <div class="announcements-container">
        @forelse($announcements as $announcement)
            <div class="announcement-item">
                <div class="announcement-header">
                    <div style="flex: 1;">
                        <div class="announcement-content">
                            {{ $announcement->content }}
                        </div>
                        <div class="announcement-meta">
                            <div class="announcement-meta-item">
                                📅 Posted: {{ $announcement->created_at->format('M j, Y \a\t g:i A') }}
                            </div>
                            @if ($announcement->created_at != $announcement->updated_at)
                                <div class="announcement-meta-item edited">
                                    ✏️ Edited: {{ $announcement->updated_at->format('M j, Y \a\t g:i A') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="announcement-actions">
                    <button onclick="openEditModal({{ $announcement->id }}, '{{ addslashes($announcement->content) }}')"
                        class="btn btn-primary btn-sm">
                        ✏️ Edit
                    </button>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                        style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this announcement?')">
                            🗑️ Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-announcements">
                <div class="empty-icon">📢</div>
                <div class="empty-title">No Announcements Yet</div>
                <div class="empty-text">Create your first announcement to communicate with voters.</div>
                <button class="btn btn-primary" onclick="openCreateModal()">
                    ➕ Create Announcement
                </button>
            </div>
        @endforelse
    </div>

    <!-- Create Announcement Modal -->
    <div id="createAnnouncementModal" class="modal-overlay" style="display: none;">
        <div class="modal">
            <h2 class="modal-title">Create New Announcement</h2>
            <form method="POST" action="{{ route('admin.announcements.store') }}">
                @csrf
                <div class="modal-form-group">
                    <textarea name="content" placeholder="Write your announcement here..." required rows="6"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeCreateModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editAnnouncementModal" class="modal-overlay" style="display: none;">
        <div class="modal">
            <h2 class="modal-title">Edit Announcement</h2>
            <form method="POST" id="editAnnouncementForm">
                @csrf
                @method('PUT')
                <div class="modal-form-group">
                    <textarea name="content" id="editContent" placeholder="Write your announcement here..." required rows="6"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Announcement</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Create Modal Functions
            function openCreateModal() {
                document.getElementById('createAnnouncementModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closeCreateModal() {
                document.getElementById('createAnnouncementModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            // Edit Modal Functions
            function openEditModal(id, content) {
                const form = document.getElementById('editAnnouncementForm');
                form.action = `/admin/announcements/${id}`;
                document.getElementById('editContent').value = content;
                document.getElementById('editAnnouncementModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                document.getElementById('editAnnouncementModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            // Close modal when clicking outside
            document.getElementById('createAnnouncementModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCreateModal();
                }
            });

            document.getElementById('editAnnouncementModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeCreateModal();
                    closeEditModal();
                }
            });
        </script>
    @endpush
@endsection
