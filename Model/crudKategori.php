<?php
    require_once ('../Connection/connection.php');

    function getAllKategori() {
        $data = array();
        $sql = "SELECT * FROM categories";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['id_kategori'] = $baris['id_kategori'];
            $data[$i]['nama_kategori'] = $baris['nama_kategori'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getKategori($idCategories) {
        $data = array();
        $sql = "SELECT * FROM categories WHERE id_kategori = '$idCategories'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['id_kategori'] = $baris['id_kategori'];
            $data[$i]['nama_kategori'] = $baris['nama_kategori'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function addKategori($idCategories, $namaCategories) {
        $koneksi = Connection();
        $sql = "INSERT INTO categories (id_kategori, nama_kategori) 
                    VALUES ('$idCategories', '$namaCategories')";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        }
    }


    function editKategori ($idCategories, $namaCategories) {
        $koneksi = Connection();
        $sql = "UPDATE categories SET nama_kategori = '$namaCategories'
                    WHERE id_kategori = '$idCategories'";
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        } 
    }

    function deleteKategori ($idCategories) {
        $koneksi = Connection();
        $sql = "DELETE FROM categories WHERE id_kategori = '$idCategories'";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
            $hasil = 1;
            mysqli_close($koneksi);
            return $hasil;
        }
    }
?>