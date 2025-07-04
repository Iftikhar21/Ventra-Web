<?php 
    require_once ('../Connection/connection.php');

    function getAllKasir() {
        $data = array();
        $sql = "SELECT * FROM ventra_kasir";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        
        // Waktu saat ini
        $currentTime = date('Y-m-d H:i:s');
        
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['kode_kasir'] = $baris['kode_kasir'];
            $data[$i]['Nama'] = $baris['Nama'];
            $data[$i]['WaktuAktif'] = $baris['WaktuAktif'];
            $data[$i]['WaktuNonAktif'] = $baris['WaktuNonAktif'];
            
            // Menentukan status berdasarkan waktu
            $activeTime = $baris['WaktuAktif'];
            $inactiveTime = $baris['WaktuNonAktif'];
            
            if ($activeTime && $inactiveTime) {
                if ($currentTime >= $activeTime && $currentTime <= $inactiveTime) {
                    $data[$i]['Status'] = 'Active';
                } else {
                    $data[$i]['Status'] = 'Inactive';
                }
            } else {
                $data[$i]['Status'] = 'Unknown';
            }
            
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getKasir($kodeKasir) {
        $data = array();
        $sql = "SELECT * FROM ventra_kasir WHERE kode_kasir = '$kodeKasir'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['kode_kasir'] = $baris['kode_kasir'];
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

    function addKasir($kodeKasir, $namaKasir, $waktuAktif, $waktuNonAktif) {
        $koneksi = Connection();
        $sql = "INSERT INTO ventra_kasir (kode_kasir, Nama, WaktuAktif, WaktuNonAktif) 
                    VALUES ('$kodeKasir', '$namaKasir', '$waktuAktif', '$waktuNonAktif')";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        }
    }

    function editKasir ($kodeKasir, $namaKasir, $waktuAktif, $waktuNonAktif) {
        $koneksi = Connection();
        $sql = "UPDATE ventra_kasir SET Nama = '$namaKasir', WaktuAktif = '$waktuAktif', WaktuNonAktif = '$waktuNonAktif'
                    WHERE kode_kasir = '$kodeKasir'";
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        } 
    }

    function deleteKasir ($kodeKasir) {
        $koneksi = Connection();
        $sql = "DELETE FROM ventra_kasir WHERE kode_kasir = '$kodeKasir'";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
            $hasil = 1;
            mysqli_close($koneksi);
            return $hasil;
        }
    }
?>