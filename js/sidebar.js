function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    const mainContent = document.querySelector('.main-content');

    if (window.innerWidth <= 768) {
        // Untuk layar kecil: toggle class show
        sidebar.classList.toggle('show');
    } else {
        // Untuk layar besar: collapse
        sidebar.classList.toggle('collapsed');
        if (sidebar.classList.contains('collapsed')) {
        toggleBtn.style.left = '90px';
        mainContent.style.marginLeft = '70px';
        } else {
        toggleBtn.style.left = '260px';
        mainContent.style.marginLeft = '250px';
        }
    }
}
  
function updateTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    document.getElementById('clock').textContent = `${hours}.${minutes}.${seconds}`;

    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                    'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    document.getElementById('date').textContent = `${day}, ${date} ${month} ${year}`;
}

setInterval(updateTime, 1000);
updateTime(); 