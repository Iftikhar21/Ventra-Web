// Fungsi untuk toggle sidebar
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.toggle-btn'); // Ubah ke querySelector biasa karena hanya ada satu tombol
    const mainContent = document.querySelector('.main-content');
    const logoFull = document.querySelector('.logo-full');
    const logoMini = document.querySelector('.logo-collapsed');

    if (!sidebar || !toggleBtn || !mainContent) {
        console.log('Elemen tidak ditemukan!');
        return;
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
            
            // Animasi logo
            if (logoFull && logoMini) {
                logoFull.style.opacity = '0';
                logoMini.style.opacity = '1';
            }
        } else {
            toggleBtn.style.left = '260px';
            mainContent.style.marginLeft = '250px';
            
            // Animasi logo
            if (logoFull && logoMini) {
                logoFull.style.opacity = '1';
                logoMini.style.opacity = '0';
            }
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

    // Inisialisasi logo sesuai state awal
    const sidebar = document.querySelector('.sidebar');
    const logoFull = document.querySelector('.logo-full');
    const logoMini = document.querySelector('.logo-collapsed');
    
    if (sidebar && sidebar.classList.contains('collapsed')) {
        if (logoFull) logoFull.style.opacity = '0';
        if (logoMini) logoMini.style.opacity = '1';
    } else {
        if (logoFull) logoFull.style.opacity = '1';
        if (logoMini) logoMini.style.opacity = '0';
    }

    // Jalankan clock pertama kali dan set interval
    updateTime();
    setInterval(updateTime, 1000);
});

// Ekspos fungsi ke global scope jika diperlukan oleh HTML
window.toggleSidebar = toggleSidebar;
window.updateTime = updateTime;