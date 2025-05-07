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
?>