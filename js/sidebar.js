// Fungsi untuk toggle sidebar
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelectorAll('.toggle-btn');
    if (toggleBtn.length > 0) {
        toggleBtn.forEach(btn => {
            btn.addEventListener('click', toggleSidebar);
        });
    } else {
        console.error('Tidak ada toggle button ditemukan!');
    }
    const mainContent = document.querySelector('.main-content');

    if (!sidebar || !toggleBtn || !mainContent) {
        console.log('Elemen tidak ditemukan!');
        return;
    } else {
        console.log('Elemen ditemukan, melanjutkan toggle sidebar...');
    }

    if (window.innerWidth <= 768) {
        // Untuk mobile: toggle class show
        sidebar.classList.toggle('show');
    } else {
        // Untuk desktop: toggle class collapsed
        sidebar.classList.toggle('collapsed');
        
        // Update posisi toggle button dan margin content
        if (sidebar.classList.contains('collapsed')) {
            toggleBtn.style.left = '90px';
            mainContent.style.marginLeft = '90px';
        } else {
            toggleBtn.style.left = '260px';
            mainContent.style.marginLeft = '250px';
        }
    }
}

// Fungsi untuk update waktu dan tanggal
function updateTime() {
    const now = new Date();
    
    // Format waktu (HH.MM.SS)
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    
    const clockElement = document.getElementById('clock');
    if (clockElement) {
        clockElement.textContent = `${hours}.${minutes}.${seconds}`;
    }

    // Format tanggal (Hari, DD Bulan YYYY)
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                   'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const dateElement = document.getElementById('date');
    if (dateElement) {
        dateElement.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
}

// Inisialisasi saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    // Setup event listener untuk toggle button
    const toggleBtn = document.querySelector('.toggle-btn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    } else {
        console.error('Toggle button tidak ditemukan!');
    }

    // Jalankan clock pertama kali dan set interval
    updateTime();
    setInterval(updateTime, 1000);
});

// Ekspos fungsi ke global scope jika diperlukan oleh HTML
window.toggleSidebar = toggleSidebar;
window.updateTime = updateTime;