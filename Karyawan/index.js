let currentPage = 1;
const rowsPerPage = 10;
let allRows = [];
let filteredRows = [];

function getFilteredRows() {
  const kodeFilter = document.getElementById("filterKode").value.toLowerCase();
  const namaFilter = document.getElementById("filterNama").value.toLowerCase();

  return allRows.filter(row => {
    const cols = row.getElementsByTagName("td");
    const kode = cols[0].textContent.toLowerCase();
    const nama = cols[1].textContent.toLowerCase();
    const waktuAktif = cols[2].textContent.split(" ")[0];
    const waktuNonAktif = cols[3].textContent.split(" ")[0];

    const matchKode = kode.includes(kodeFilter);
    const matchNama = nama.includes(namaFilter);

    return matchKode && matchNama;
  });
}

function showPage(page) {
  filteredRows = getFilteredRows();
  const start = (page - 1) * rowsPerPage;
  const end = start + rowsPerPage;

  allRows.forEach(row => row.style.display = "none");

  for (let i = start; i < end && i < filteredRows.length; i++) {
    filteredRows[i].style.display = "";
  }

  document.getElementById('pageInfo').textContent = `Halaman ${page} dari ${Math.max(1, Math.ceil(filteredRows.length / rowsPerPage))}`;
}

function filterTable() {
  currentPage = 1;
  showPage(currentPage);
}

function prevPage() {
  if (currentPage > 1) {
    currentPage--;
    showPage(currentPage);
  }
}

function nextPage() {
  if (currentPage < Math.ceil(filteredRows.length / rowsPerPage)) {
    currentPage++;
    showPage(currentPage);
  }
}

window.onload = () => {
  allRows = Array.from(document.querySelectorAll("#myTable tr"));
  filterTable();
};