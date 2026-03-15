// ===== CAMPUSVOTE ADMIN JAVASCRIPT =====

document.addEventListener("DOMContentLoaded", () => {
    
    // ===== Admin Login Functionality =====
    const adminForm = document.getElementById('adminForm');
    if (adminForm) {
        adminForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Simple button click - navigate to admin dashboard
            window.location.href = '/admin/dashboard';
        });
    }

    // ===== Back to User Login =====
    const backUser = document.getElementById('backUser');
    if (backUser) {
        backUser.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = '/';
        });
    }

    // ===== Logout Button =====
    const logoutBtn = document.getElementById('logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/admin/login';
            }
        });
    }

    // ===== Create Election Functionality =====
    const addPositionBtn = document.getElementById('addPositionBtn');
    const positionsContainer = document.getElementById('positionsContainer');
    const saveElection = document.getElementById('saveElection');
    const cancelBtn = document.getElementById('cancelBtn');

    let positionCount = 0;

    if (addPositionBtn) {
        addPositionBtn.addEventListener('click', () => {
            addPositionField();
        });
    }

    if (saveElection) {
        saveElection.addEventListener('click', () => {
            const electionTitle = document.getElementById('electionTitle').value.trim();
            if (!electionTitle) {
                alert('Please enter an election title.');
                return;
            }
            alert('Election created successfully!');
            window.location.href = '/admin/dashboard';
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to cancel?')) {
                window.location.href = '/admin/dashboard';
            }
        });
    }

    function addPositionField() {
        positionCount++;
        const positionBox = document.createElement('div');
        positionBox.className = 'position-box';
        positionBox.innerHTML = `
            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 15px;">
                <label style="font-weight: bold;">Position Name:</label>
                <input type="text" class="position-name" placeholder="Enter position name" 
                    style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <button type="button" class="delete-btn delete-position" style="padding: 5px 10px;">Delete</button>
            </div>
            <div class="candidates-container">
                <div class="candidate-box">
                    <input type="text" class="candidate-input" placeholder="Candidate 1 Name">
                    <button type="button" class="delete-btn delete-candidate" style="padding: 5px 10px;">Delete</button>
                </div>
                <div class="candidate-box">
                    <input type="text" class="candidate-input" placeholder="Add new candidate">
                    <button type="button" class="add-btn add-candidate" style="padding: 5px 10px;">+ Add Candidate</button>
                </div>
            </div>
        `;

        if (positionsContainer) {
            positionsContainer.appendChild(positionBox);
        }

        // Delete position
        const deleteBtn = positionBox.querySelector('.delete-position');
        deleteBtn.addEventListener('click', () => {
            if (confirm('Delete this position?')) {
                positionBox.remove();
            }
        });

        // Add candidate
        const addCandidateBtn = positionBox.querySelector('.add-candidate');
        addCandidateBtn.addEventListener('click', () => {
            const candidatesContainer = positionBox.querySelector('.candidates-container');
            const candidateBox = document.createElement('div');
            candidateBox.className = 'candidate-box';
            candidateBox.innerHTML = `
                <input type="text" class="candidate-input" placeholder="Candidate Name">
                <button type="button" class="delete-btn delete-candidate" style="padding: 5px 10px;">Delete</button>
            `;
            candidatesContainer.insertBefore(candidateBox, addCandidateBtn.parentNode);

            candidateBox.querySelector('.delete-candidate').addEventListener('click', () => {
                if (confirm('Delete this candidate?')) {
                    candidateBox.remove();
                }
            });
        });

        // Delete candidate
        const deleteCandidateBtns = positionBox.querySelectorAll('.delete-candidate');
        deleteCandidateBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (confirm('Delete this candidate?')) {
                    btn.closest('.candidate-box').remove();
                }
            });
        });
    }

    // ===== Announcements Functionality =====
    const openForm = document.getElementById('openForm');
    const cancelForm = document.getElementById('cancelForm');
    const saveAnnouncement = document.getElementById('saveAnnouncement');
    const formContainer = document.getElementById('formContainer');

    if (openForm) {
        openForm.addEventListener('click', () => {
            formContainer.style.display = 'flex';
        });
    }

    if (cancelForm) {
        cancelForm.addEventListener('click', () => {
            formContainer.style.display = 'none';
        });
    }

    if (saveAnnouncement) {
        saveAnnouncement.addEventListener('click', () => {
            const announcementText = document.getElementById('announcementText').value.trim();
            if (!announcementText) {
                alert('Please enter an announcement text.');
                return;
            }
            alert('Announcement created successfully!');
            formContainer.style.display = 'none';
            document.getElementById('announcementText').value = '';
        });
    }

    // ===== Dashboard Functions =====
    window.refreshDashboard = function() {
        alert('Dashboard data refreshed!');
    };

    window.exportResults = function() {
        alert('Results exported successfully!');
    };

    // ===== Voter Delete Confirmation =====
    window.confirmDelete = function(button) {
        if (confirm('Are you sure you want to delete this voter?')) {
            button.closest('tr').remove();
        }
    };
});