// Format number with commas
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Format currency
function formatCurrency(num) {
  return 'Rp' + formatNumber(num);
}

// Calculate statistics
function calculateStats(data, isCurrency = false) {
  const values = data.map(item => isCurrency ? parseFloat(item.total_pendapatan) : parseInt(item.jumlah));
  const sum = values.reduce((a, b) => a + b, 0);
  const avg = sum / values.length;
  const max = Math.max(...values);
  const min = Math.min(...values);

  // Format avg dengan 2 digit desimal untuk currency, bulatkan untuk non-currency
  const formattedAvg = isCurrency 
    ? parseFloat(avg.toFixed(2)) // Membatasi ke 2 desimal dan konversi kembali ke float
    : Math.round(avg); // Bulatkan untuk nilai non-currency

  return {
    sum: isCurrency ? formatCurrency(sum) : formatNumber(sum),
    avg: isCurrency ? formatCurrency(formattedAvg) : formatNumber(formattedAvg),
    max: isCurrency ? formatCurrency(max) : formatNumber(max),
    min: isCurrency ? formatCurrency(min) : formatNumber(min),
    rawValues: values
  };
}

// Generate details HTML
function generateDetailsHTML(data, isCurrency = false) {
  let html = '<table style="width:100%; border-collapse: collapse;">';
  html += '<tr style="background-color: #6c5ce7; color: white;">';
  html += '<th style="padding: 8px; text-align: left;">Bulan</th>';
  html += '<th style="padding: 8px; text-align: right;">' + (isCurrency ? 'Pendapatan' : 'Jumlah Transaksi') + '</th>';
  html += '</tr>';

  data.forEach((item, index) => {
    const value = isCurrency ? formatCurrency(parseFloat(item.total_pendapatan)) : formatNumber(parseInt(item.jumlah));
    html += `<tr style="border-bottom: 1px solid #ddd; background-color: ${index % 2 === 0 ? '#ffffff' : '#f8f9fa'}">`;
    html += `<td style="padding: 8px;">${item.bulan}</td>`;
    html += `<td style="padding: 8px; text-align: right;">${value}</td>`;
    html += '</tr>';
  });

  html += '</table>';
  return html;
}

// Export to PDF
function exportToPDF(sectionId) {
  const {
    jsPDF
  } = window.jspdf;
  const element = document.getElementById(sectionId);
  const title = element.querySelector('h4').textContent;

  // Show loading state
  const exportBtn = document.querySelector(`#${sectionId} .export-btn`);
  const originalText = exportBtn.textContent;
  exportBtn.textContent = "Membuat PDF...";
  exportBtn.disabled = true;

  html2canvas(element).then(canvas => {
    const imgData = canvas.toDataURL('image/png');
    const pdf = new jsPDF('p', 'mm', 'a4');
    const imgWidth = 210; // A4 width in mm
    const pageHeight = 295; // A4 height in mm
    const imgHeight = canvas.height * imgWidth / canvas.width;
    let heightLeft = imgHeight;
    let position = 0;

    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
    heightLeft -= pageHeight;

    while (heightLeft >= 0) {
      position = heightLeft - imgHeight;
      pdf.addPage();
      pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;
    }

    pdf.save(`${title}.pdf`);
  }).finally(() => {
    // Restore button state
    exportBtn.textContent = originalText;
    exportBtn.disabled = false;
  });
}

// Load data from server
document.addEventListener("DOMContentLoaded", function () {
  // Load transaction data
  fetch('chart_data_transaksi.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      processChartData(
        data,
        'chartBarTransaction',
        'Jumlah Transaksi per Bulan',
        'Jumlah Transaksi',
        false
      );
    })
    .catch(error => {
      console.error('Error loading transaction data:', error);
      document.getElementById('transaction-details').innerHTML =
        '<div style="color: red;">Gagal memuat data transaksi: ' + error.message + '</div>';
    });

  // Load profit data
  fetch('chart_data_pendapatan.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      processChartData(
        data,
        'chartBarProfit',
        'Jumlah Pendapatan per Bulan',
        'Jumlah Pendapatan (Rp)',
        true
      );
    })
    .catch(error => {
      console.error('Error loading profit data:', error);
      document.getElementById('profit-details').innerHTML =
        '<div style="color: red;">Gagal memuat data pendapatan: ' + error.message + '</div>';
    });
});

// Variabel global untuk menyimpan instance chart
let transactionChart = null;
let profitChart = null;

// Fungsi untuk memproses data chart
function processChartData(data, chartId, chartLabel, yAxisLabel, isCurrency) {
  if (!data || data.length === 0) {
    document.getElementById(chartId.replace('chartBar', '').toLowerCase() + '-details').innerHTML =
      '<div style="color: red;">Tidak ada data yang tersedia</div>';
    return;
  }

  const labels = data.map(item => item.bulan);
  const values = data.map(item => isCurrency ? parseFloat(item.total_pendapatan) : parseInt(item.jumlah));

  // Calculate statistics
  const stats = calculateStats(data, isCurrency);

  // Update summary cards
  if (chartId === 'chartBarTransaction') {
    document.getElementById('total-transactions').textContent = stats.sum;
    document.getElementById('avg-transactions').textContent = stats.avg;
    document.getElementById('max-transactions').textContent = stats.max;
    document.getElementById('min-transactions').textContent = stats.min;
    document.getElementById('transaction-details').innerHTML = generateDetailsHTML(data, false);
  } else {
    document.getElementById('total-profit').textContent = stats.sum;
    document.getElementById('avg-profit').textContent = stats.avg;
    document.getElementById('max-profit').textContent = stats.max;
    document.getElementById('min-profit').textContent = stats.min;
    document.getElementById('profit-details').innerHTML = generateDetailsHTML(data, true);
  }

  // Get canvas context
  const ctx = document.getElementById(chartId).getContext('2d');

  // Destroy previous chart if exists
  if (chartId === 'chartBarTransaction' && transactionChart) {
    transactionChart.destroy();
  } else if (chartId === 'chartBarProfit' && profitChart) {
    profitChart.destroy();
  }

  // Create new chart
  const newChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: chartLabel,
        data: values,
        backgroundColor: 'rgba(108, 92, 231, 0.7)',
        borderColor: 'rgba(108, 92, 231, 1)',
        borderWidth: 1,
        borderRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              return isCurrency ?
                formatCurrency(context.raw) :
                formatNumber(context.raw);
            }
          }
        },
        legend: {
          position: 'top',
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: yAxisLabel
          },
          ticks: {
            callback: function (value) {
              return isCurrency ?
                formatCurrency(value) :
                formatNumber(value);
            }
          }
        },
        x: {
          title: {
            display: true,
            text: 'Bulan'
          },
          grid: {
            display: false
          }
        }
      },
      animation: {
        duration: 1000,
        easing: 'easeInOutQuad'
      }
    }
  });

  // Save chart reference
  if (chartId === 'chartBarTransaction') {
    transactionChart = newChart;
  } else {
    profitChart = newChart;
  }
}

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
