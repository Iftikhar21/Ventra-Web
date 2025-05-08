document.addEventListener("DOMContentLoaded", function () {
    fetch('chart_data.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.bulan);
            const values = data.map(item => item.jumlah);

            const ctx = document.getElementById('chartBar').getContext('2d');
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
