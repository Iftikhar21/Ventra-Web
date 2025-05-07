<?php    
    function Connection() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "bacs5153_recode";

        $koneksi = mysqli_connect($servername, $username, $password, $database);
        if(!$koneksi) {
            die ("Koneksi Gagal!".mysqli_connect_error());
        }
        return $koneksi;
    }
?>