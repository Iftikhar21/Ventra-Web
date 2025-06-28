<?php
    require_once ('../Connection/connection.php');

    function getAllEvent() {
        $data = array();
        $sql = "SELECT * FROM  ventra_event";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['id_event'] = $baris['id_event'];
            $data[$i]['nama_event'] = $baris['nama_event'];
            $data[$i]['total_diskon'] = $baris['total_diskon'];
            $data[$i]['waktu_aktif'] = $baris['waktu_aktif'];
            $data[$i]['waktu_non_aktif'] = $baris['waktu_non_aktif'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getEvent($idEvent) {
        $sql = "SELECT * FROM ventra_event WHERE id_event = '$idEvent'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $data = mysqli_fetch_assoc($hasil); // langsung ambil satu baris
        mysqli_close($koneksi);
        return $data;
    }


    function addEvent($idEvent, $namaEvent, $totalDiskon, $waktuAktif, $waktuNonAktif) {
        $koneksi = Connection();
        $sql = "INSERT INTO ventra_event (id_event, nama_event, total_diskon, waktu_aktif, waktu_non_aktif) 
                    VALUES ('$idEvent', '$namaEvent', '$totalDiskon', '$waktuAktif', '$waktuNonAktif')";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        }
    }

    function editEvent ($idEvent, $namaEvent, $totalDiskon, $waktuAktif, $waktuNonAktif) {
        $koneksi = Connection();
        $sql = "UPDATE ventra_event SET nama_event = '$namaEvent', total_diskon = '$totalDiskon', waktu_aktif = '$waktuAktif', waktu_non_aktif = '$waktuNonAktif'
                    WHERE id_event = '$idEvent'";
        if (mysqli_query($koneksi, $sql)) {
             $hasil = 1;
             mysqli_close($koneksi);
             return $hasil;
        } 
    }

    function deleteEvent ($idEvent) {
        $koneksi = Connection();
        $sql = "DELETE FROM ventra_event WHERE id_event = '$idEvent'";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
            $hasil = 1;
            mysqli_close($koneksi);
            return $hasil;
        }
    }

    function getAvailableProductsForEvent($idEvent) {
        $conn = Connection();
        
        $query = "SELECT vp.*, c.nama_kategori 
                FROM ventra_produk vp 
                JOIN categories c ON vp.Kategori = c.id_kategori
                WHERE vp.id NOT IN (
                    SELECT id_produk FROM ventra_detail_event WHERE id_event = ?
                )";
                
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idEvent);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    function addProductsToEvent($idEvent, $productIds) {
        $koneksi = Connection();
        $success = true;
        
        foreach ($productIds as $idBarang) {
            $sql = "INSERT INTO ventra_detail_event (id_event, id_produk) VALUES ('$idEvent', '$idBarang')";
            if (!mysqli_query($koneksi, $sql)) {
                $success = false;
                break;
            }
        }
        
        mysqli_close($koneksi);
        return $success;
    }

    function removeProductFromEvent($idEvent, $idBarang) {
        $koneksi = Connection();
        $sql = "DELETE FROM ventra_detail_event WHERE id_event = '$idEvent' AND id_produk = '$idBarang'";
        $hasil = 0;
        if (mysqli_query($koneksi, $sql)) {
            $hasil = 1;
        }
        mysqli_close($koneksi);
        return $hasil;
    }


?>