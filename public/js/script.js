// ===== CAMPUSVOTE USER JAVASCRIPT =====

document.addEventListener("DOMContentLoaded", () => {
    
    // ===== Student Login Functionality =====
    const studentForm = document.getElementById('studentForm');
    if (studentForm) {
        studentForm.addEventListener('submit', (e) => {
            e.preventDefault();
            // Simple button click - navigate to user index
            window.location.href = '/user/index';
        });
    }

    // ===== Logout Button =====
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = '/';
            }
        });
    }

    // ===== Home Page Card Navigation =====
    const voteNowCard = document.getElementById('voteNowCard');
    if (voteNowCard) {
        voteNowCard.addEventListener('click', () => {
            window.location.href = '/user/vote-now';
        });
    }

    const viewVotesCard = document.getElementById('viewVotesCard');
    if (viewVotesCard) {
        viewVotesCard.addEventListener('click', () => {
            window.location.href = '/user/view-votes';
        });
    }

    const viewResultsCard = document.getElementById('viewResultsCard');
    if (viewResultsCard) {
        viewResultsCard.addEventListener('click', () => {
            window.location.href = '/user/view-results';
        });
    }

    // ===== Vote Page - Only one checkbox per position =====
    const voteSections = document.querySelectorAll('.position-box');
    voteSections.forEach((section) => {
        const checkboxes = section.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach((box) => {
            box.addEventListener('change', () => {
                if (box.checked) {
                    checkboxes.forEach((other) => {
                        if (other !== box) other.checked = false;
                    });
                }
            });
        });
    });

    // ===== Submit Vote Button =====
    const submitBtn = document.querySelector('.submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', () => {
            alert('✅ Your vote has been submitted successfully!');
            window.location.href = '/user/view-votes';
        });
    }

    // ===== Profile Buttons =====
    const profileButtons = document.querySelectorAll('.profile-btns button');
    if (profileButtons.length) {
        profileButtons[0].addEventListener('click', () => {
            window.location.href = '/';
        });
        profileButtons[1].addEventListener('click', () => {
            window.location.href = '/';
        });
    }
});