let currentPage = 1;
const rowsPerPage = 5;
let filteredRows = [];

function filterTable() {
    const kodeFilter = document.getElementById("filterKode").value.toLowerCase();
    const namaFilter = document.getElementById("filterNama").value.toLowerCase();
    const waktuAktifFilter = document.getElementById("filterWaktuAktif").value;
    const waktuNonAktifFilter = document.getElementById("filterWaktuNonAktif").value;

    const table = document.getElementById("myTable");
    const rows = table.getElementsByTagName("tr");

    filteredRows = []; // Kosongkan dulu

    for (let i = 0; i < rows.length; i++) {
        const cols = rows[i].getElementsByTagName("td");
        if (cols.length < 5) continue;

        const kode = cols[0].textContent.toLowerCase();
        const nama = cols[1].textContent.toLowerCase();
        const waktuAktif = cols[3].textContent.trim().split(" ")[0]; // posisi kolom 3
        const waktuNonAktif = cols[4].textContent.trim().split(" ")[0]; // posisi kolom 4

        const matchKode = kode.includes(kodeFilter);
        const matchNama = nama.includes(namaFilter);
        const matchWaktuAktif = !waktuAktifFilter || waktuAktif === waktuAktifFilter;
        const matchWaktuNonAktif = !waktuNonAktifFilter || waktuNonAktif === waktuNonAktifFilter;

        if (matchKode && matchNama && matchWaktuAktif && matchWaktuNonAktif) {
            filteredRows.push(rows[i]); // tambahkan ke array jika cocok
        }
    }

    showPage(1); // Tampilkan halaman pertama dari hasil filter
}

function showPage(page) {
  const totalRows = filteredRows.length;
  const totalPages = Math.ceil(totalRows / rowsPerPage);

  // Batasi nilai halaman
  if (page < 1) page = 1;
  if (page > totalPages) page = totalPages;

  // Sembunyikan semua baris dan tampilkan sesuai halaman
  const table = document.getElementById("myTable");
  const rows = table.getElementsByTagName("tr");

  // Sembunyikan semua baris terlebih dahulu
  for (let i = 0; i < rows.length; i++) {
    rows[i].style.display = "none";
  }

  // Tampilkan baris yang sesuai dengan halaman yang dipilih
  const startIdx = (page - 1) * rowsPerPage;
  const endIdx = page * rowsPerPage;

  for (let i = startIdx; i < endIdx && i < totalRows; i++) {
    filteredRows[i].style.display = "";
  }

  document.getElementById("pageInfo").innerText = `Halaman ${page} dari ${totalPages}`;
  currentPage = page;
}

function nextPage() {
  showPage(currentPage + 1);
}

function prevPage() {
  showPage(currentPage - 1);
}

document.addEventListener("DOMContentLoaded", () => {
  filterTable();  // Panggil filterTable saat halaman pertama kali dimuat
});
