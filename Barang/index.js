function filterTable(colIndex, filterValue) {
    const table = document.getElementById("myTable");
    const rows = table.getElementsByTagName("tr");
    const value = filterValue.toLowerCase();

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");
        if (cells[colIndex]) {
            let cellText = cells[colIndex].innerText || cells[colIndex].textContent;
            cellText = cellText.toLowerCase();

            rows[i].style.display = cellText.indexOf(value) > -1 || value === "" ? "" : "none";
        }
    }
}

let currentPage = 1;
const rowsPerPage = 5;

function showPage(page) {
    const table = document.getElementById("myTable");
    const rows = table.getElementsByTagName("tr");
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    // Batasi nilai page
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    // Tampilkan/Hilangkan baris
    for (let i = 0; i < totalRows; i++) {
        rows[i].style.display = (i >= (page - 1) * rowsPerPage && i < page * rowsPerPage) ? "" : "none";
    }

    document.getElementById("pageInfo").innerText = "Halaman " + page + " dari " + totalPages;
    currentPage = page;
}

function nextPage() {
    showPage(currentPage + 1);
}

function prevPage() {
    showPage(currentPage - 1);
}

// Tampilkan halaman pertama saat load
document.addEventListener("DOMContentLoaded", () => {
    showPage(1);
});