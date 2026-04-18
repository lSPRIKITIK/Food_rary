document.addEventListener('DOMContentLoaded', function() {
    const avatarBtn = document.getElementById('avatar-btn');
    const dropdown = document.getElementById('profile-dropdown');
    const container = document.getElementById('profile-menu-container');

    if (avatarBtn && dropdown && container) {
        
        avatarBtn.addEventListener('click', function() {
            dropdown.classList.toggle('hidden');
        });
        window.addEventListener('click', function(event) {
            if (!container.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});