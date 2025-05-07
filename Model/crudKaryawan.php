<?php 
    require_once ('../Connection/connection.php');

    function getAllKasir() {
        $data = array();
        $sql = "SELECT * FROM ventra_kasir";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['ID'] = $baris['ID'];
            $data[$i]['Nama'] = $baris['Nama'];
            $data[$i]['WaktuAktif'] = $baris['WaktuAktif'];
            $data[$i]['WaktuNonAktif'] = $baris['WaktuNonAktif'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getKasir($Kode_Kasir) {
        $data = array();
        $sql = "SELECT * FROM ventra_kasir WHERE ID = '$Kode_Kasir'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['ID'] = $baris['ID'];
            $data[$i]['Nama'] = $baris['Nama'];
            $data[$i]['WaktuAktif'] = $baris['WaktuAktif'];
            $data[$i]['WaktuNonAktif'] = $baris['WaktuNonAktif'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTotalKasir () {
        $koneksi = Connection();
        $sql = "SELECT COUNT(*) AS jumlah_kolom FROM ventra_kasir;";
        $result = $koneksi->query($sql);
        $jumlahKasir = $result->fetch_assoc()['jumlah_kolom'];
        return $jumlahKasir;
    }
?>