<?php 
    require_once ('../Connection/connection.php');

    function getAllTransaksi() {
        $data = array();
        $sql = "SELECT * FROM ventra_transaksi";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['ID_Transaksi'] = $baris['ID_Transaksi'];
            $data[$i]['Total'] = $baris['Total'];
            $data[$i]['Payment'] = $baris['Payment'];
            $data[$i]['Kasir'] = $baris['Kasir'];
            $data[$i]['tanggal_transaksi'] = $baris['tanggal_transaksi'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTransaksi($Kode_Transaksi) {
        $data = array();
        $sql = "SELECT * FROM ventra_transaksi WHERE ID_Transaksi = '$Kode_Transaksi'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['ID_Transaksi'] = $baris['ID_Transaksi'];
            $data[$i]['Total'] = $baris['Total'];
            $data[$i]['Payment'] = $baris['Payment'];
            $data[$i]['Kasir'] = $baris['Kasir'];
            $data[$i]['tanggal_transaksi'] = $baris['tanggal_transaksi'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTotalTransaksi () {
        $koneksi = Connection();
        $sql = "SELECT COUNT(*) AS jumlah_kolom
                                    FROM ventra_transaksi
                                    WHERE MONTH(tanggal_transaksi) = MONTH(CURDATE())
                                    AND YEAR(tanggal_transaksi) = YEAR(CURDATE())";
        $result = $koneksi->query($sql);
        $jumlahTransaksi = $result->fetch_assoc()['jumlah_kolom'];
        return $jumlahTransaksi;
    }

    function getTransaksiPerBulanTahunIni() {
        $koneksi = Connection();
        $sql = "SELECT MONTH(tanggal_transaksi) as bulan, COUNT(*) as jumlah 
                FROM ventra_transaksi 
                WHERE YEAR(tanggal_transaksi) = YEAR(CURDATE())
                GROUP BY MONTH(tanggal_transaksi)";
        
        $hasil = mysqli_query($koneksi, $sql);
        
        // Buat array bulan default (1–12)
        $bulanLengkap = [];
        for ($i = 1; $i <= 12; $i++) {
            $namaBulan = date("F", mktime(0, 0, 0, $i, 1));
            $bulanLengkap[$i] = [
                'bulan' => $namaBulan,
                'jumlah' => 0
            ];
        }
    
        // Isi data yang ada dari database
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $bulan = (int)$baris['bulan'];
            $bulanLengkap[$bulan]['jumlah'] = (int)$baris['jumlah'];
        }
    
        mysqli_close($koneksi);
    
        // Kembalikan array dengan urutan bulan 1–12
        return array_values($bulanLengkap);
    }    
?>