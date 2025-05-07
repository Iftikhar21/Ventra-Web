<?php
    require_once ('../Connection/connection.php');

    function getAllBarang() {
        $data = array();
        $sql = "SELECT * FROM ventra_produk";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['Kode_Brg'] = $baris['Kode_Brg'];
            $data[$i]['Nama_Brg'] = $baris['Nama_Brg'];
            $data[$i]['HargaJual'] = $baris['HargaJual'];
            $data[$i]['Ukuran'] = $baris['Ukuran'];
            $data[$i]['Bahan'] = $baris['Bahan'];
            $data[$i]['Gambar'] = $baris['Gambar'];
            $data[$i]['Kategori'] = $baris['Kategori'];
            $data[$i]['Stock'] = $baris['Stock'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getBarang($Kode_Brg) {
        $data = array();
        $sql = "SELECT * FROM ventra_produk WHERE Kode_Brg = '$Kode_Brg'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['Kode_Brg'] = $baris['Kode_Brg'];
            $data[$i]['Nama_Brg'] = $baris['Nama_Brg'];
            $data[$i]['Modal'] = $baris['Modal'];
            $data[$i]['HargaJual'] = $baris['HargaJual'];
            $data[$i]['Ukuran'] = $baris['Ukuran'];
            $data[$i]['Bahan'] = $baris['Bahan'];
            $data[$i]['Gambar'] = $baris['Gambar'];
            $data[$i]['Kategori'] = $baris['Kategori'];
            $data[$i]['Stock'] = $baris['Stock'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTotalBarang () {
        $koneksi = Connection();
        $sql = "SELECT COUNT(*) AS jumlah_kolom FROM ventra_produk;";
        $result = $koneksi->query($sql);
        $jumlahBarang = $result->fetch_assoc()['jumlah_kolom'];
        return $jumlahBarang;
    }

    function getBarangMenipis () {
        $data = array();
        $sql = "SELECT * FROM ventra_produk WHERE Stock <= 5";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['Kode_Brg'] = $baris['Kode_Brg'];
            $data[$i]['Nama_Brg'] = $baris['Nama_Brg'];
            $data[$i]['HargaJual'] = $baris['HargaJual'];
            $data[$i]['Ukuran'] = $baris['Ukuran'];
            $data[$i]['Bahan'] = $baris['Bahan'];
            $data[$i]['Gambar'] = $baris['Gambar'];
            $data[$i]['Kategori'] = $baris['Kategori'];
            $data[$i]['Stock'] = $baris['Stock'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTotalBarangMenipis () {
        $koneksi = Connection();
        $sql = "SELECT COUNT(*) AS jumlah_kolom FROM ventra_produk WHERE Stock <= 5;";
        $result = $koneksi->query($sql);
        $jumlahBarangMenipis = $result->fetch_assoc()['jumlah_kolom'];
        return $jumlahBarangMenipis;
    }

    function addBarang($kodeBarang, $namaBarang, $hargaJual, $modal, $ukuran, $bahan, $gambar, $kategori, $stock) {
        $koneksi = Connection();
        $sql = "INSERT INTO ventra_produk (Kode_Brg, Nama_Brg, HargaJual, Modal, Ukuran, Bahan, Gambar, Kategori, Stock) VALUES ('$kodeBarang', '$namaBarang', '$hargaJual', '$modal', '$ukuran', '$bahan', '$gambar', '$kategori', '$stock')";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        }
    }

    function editBarang ($kodeBarang, $namaBarang, $hargaJual, $modal, $ukuran, $bahan, $gambar, $kategori, $stock) {
        $koneksi = Connection();
        $sql = "UPDATE ventra_produk SET Nama_Brg = '$namaBarang', HargaJual = '$hargaJual', Modal = '$modal', Ukuran = '$ukuran', 
                    Bahan = '$bahan', Gambar = '$gambar', Kategori = '$kategori', Stock = '$stock' WHERE Kode_Brg = '$kodeBarang'";
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        } 
    }

    function deleteBarang ($kodeBarang) {
        $koneksi = Connection();
        $sql = "DELETE FROM ventra_produk WHERE Kode_Brg = '$kodeBarang'";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
            $hasil = 1;
            mysqli_close($koneksi);
            return $hasil;
        }
    }
?>