// Global variables
let currentPage = 1;
const rowsPerPage = 5; // Adjust as needed
let filteredRows = [];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  initializePagination();

  // Add event listeners for filters
  document.getElementById('filterKode')?.addEventListener('input', function () {
    filterTable(0, this.value);
  });

  document.getElementById('filterNama')?.addEventListener('input', function () {
    filterTable(1, this.value);
  });
});

// Initialize pagination
function initializePagination() {
  const table = document.getElementById('myTable');
  if (!table) return;

  const rows = table.getElementsByTagName('tr');
  filteredRows = Array.from(rows).filter(row => row.getElementsByTagName('td').length > 0);

  displayPage(currentPage);
}

// Display specific page
function displayPage(page) {
  const startIndex = (page - 1) * rowsPerPage;
  const endIndex = startIndex + rowsPerPage;

  // Hide all rows first
  filteredRows.forEach(row => row.style.display = 'none');

  // Show rows for current page
  for (let i = startIndex; i < endIndex && i < filteredRows.length; i++) {
    filteredRows[i].style.display = '';
  }

  updatePageInfo();
}

// Update page information
function updatePageInfo() {
  const totalRows = filteredRows.length;
  const totalPages = Math.ceil(totalRows / rowsPerPage);
  const pageInfoElement = document.getElementById('pageInfo');

  if (pageInfoElement) {
    if (totalRows === 0) {
      pageInfoElement.textContent = 'Tidak ada data';
    } else {
      const startItem = (currentPage - 1) * rowsPerPage + 1;
      const endItem = Math.min(currentPage * rowsPerPage, totalRows);
      pageInfoElement.textContent = `Halaman ${currentPage} dari ${totalPages} (${startItem}-${endItem} dari ${totalRows} item)`;
    }
  }

  // Update button states
  const prevBtn = document.querySelector('button[onclick="prevPage()"]');
  const nextBtn = document.querySelector('button[onclick="nextPage()"]');

  if (prevBtn) prevBtn.disabled = currentPage <= 1;
  if (nextBtn) nextBtn.disabled = currentPage >= totalPages || totalPages === 0;
}

// Filter table function
function filterTable(columnIndex, searchText) {
  const table = document.getElementById('myTable');
  if (!table) return;

  const rows = table.getElementsByTagName('tr');
  filteredRows = [];

  for (let i = 0; i < rows.length; i++) {
    const row = rows[i];
    const cells = row.getElementsByTagName('td');

    if (cells.length === 0) continue; // Skip header row

    const cell = cells[columnIndex];
    const cellValue = cell.textContent || cell.innerText;
    const searchValue = searchText.toLowerCase();

    if (searchText === '' || cellValue.toLowerCase().includes(searchValue)) {
      row.style.display = '';
      filteredRows.push(row);
    } else {
      row.style.display = 'none';
    }
  }

  currentPage = 1;
  displayPage(currentPage);
}

// Navigation functions - must be global
window.prevPage = function () {
  if (currentPage > 1) {
    currentPage--;
    displayPage(currentPage);
  }
}

window.nextPage = function () {
  const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
  if (currentPage < totalPages) {
    currentPage++;
    displayPage(currentPage);
  }
}