document.addEventListener("DOMContentLoaded", function () {
  fetch('chart_data_transaksi.php')
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.bulan);
      const values = data.map(item => item.jumlah);

      const ctx = document.getElementById('chartBarTransaction').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Transaksi per Bulan',
            data: values,
            backgroundColor: 'rgba(153, 102, 255, 0.6)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1,
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah Transaksi'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Bulan'
              }
            }
          }
        }
      });
    })
    .catch(error => {
      console.error('Gagal mengambil data chart:', error);
    });
});

document.addEventListener("DOMContentLoaded", function () {
  fetch('chart_data_pendapatan.php')
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.bulan);
      const values = data.map(item => item.total_pendapatan);

      const ctx = document.getElementById('chartBarProfit').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Pendapatan per Bulan',
            data: values,
            backgroundColor: 'rgba(153, 102, 255, 0.6)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1,
            borderRadius: 5
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah Pendapatan'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Bulan'
              }
            }
          }
        }
      });
    })
    .catch(error => {
      console.error('Gagal mengambil data chart:', error);
    });
});

// Scroll the catalog horizontally
function scrollCatalog(direction) {
  const catalog = document.getElementById('productCatalog');
  const scrollAmount = 240; // Adjust based on card width + gap
  catalog.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });

  // Update dots after scrolling
  setTimeout(updateActiveDot, 300);
}

// Scroll to specific page
function scrollToPage(pageIndex) {
  const catalog = document.getElementById('productCatalog');
  const cardWidth = 220; // Width of product card
  const gap = 24; // Gap between cards
  const visibleCards = Math.floor(catalog.clientWidth / (cardWidth + gap));
  const scrollAmount = pageIndex * (visibleCards * (cardWidth + gap));

  catalog.scrollTo({
    left: scrollAmount,
    behavior: 'smooth'
  });

  // Update active dot
  const dots = document.querySelectorAll('.dot');
  dots.forEach((dot, index) => {
    if (index === pageIndex) {
      dot.classList.add('active');
    } else {
      dot.classList.remove('active');
    }
  });
}

// Update active dot based on scroll position
function updateActiveDot() {
  const catalog = document.getElementById('productCatalog');
  const dots = document.querySelectorAll('.dot');
  const cardWidth = 220; // Width of product card
  const gap = 24; // Gap between cards
  const visibleCards = Math.floor(catalog.clientWidth / (cardWidth + gap));
  const scrollPosition = catalog.scrollLeft;
  const maxScroll = catalog.scrollWidth - catalog.clientWidth;

  // Calculate which page we're on
  const pageCount = dots.length;
  const pageSize = maxScroll / (pageCount - 1);
  let currentPage = Math.round(scrollPosition / pageSize);

  // Edge case for last page
  if (scrollPosition + 5 >= maxScroll) currentPage = pageCount - 1;

  // Update dots
  dots.forEach((dot, index) => {
    if (index === currentPage) {
      dot.classList.add('active');
    } else {
      dot.classList.remove('active');
    }
  });
}

// Handle category filter clicks
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');

    // Here you would add filter functionality
    // For demonstration, we'll just scroll back to the start
    scrollToPage(0);
  });
});

// Add hover effects for product cards
document.querySelectorAll('.product-card').forEach(card => {
  card.addEventListener('mouseenter', function () {
    this.style.transform = 'translateY(-10px)';
    this.style.boxShadow = 'var(--shadow-lg)';
  });

  card.addEventListener('mouseleave', function () {
    this.style.transform = 'translateY(0)';
    this.style.boxShadow = 'var(--shadow-sm)';
  });
});

// Add favorite toggle functionality
document.querySelectorAll('.action-btn:not(.primary)').forEach(btn => {
  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    const icon = this.querySelector('i');
    if (icon.classList.contains('far')) {
      icon.classList.remove('far');
      icon.classList.add('fas');
      icon.style.color = '#ff4757';
    } else {
      icon.classList.remove('fas');
      icon.classList.add('far');
      icon.style.color = '';
    }
  });
});

// Listen for scroll events on the catalog
document.getElementById('productCatalog').addEventListener('scroll', function () {
  updateActiveDot();
});

// Initialize scroll position
window.addEventListener('load', function () {
  scrollToPage(0);
});
