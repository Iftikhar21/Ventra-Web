<?php 

require_once ('../Connection/connection.php');

function getAdminById($id) {
    $data = array();
    $sql = "SELECT * FROM admin WHERE id = '$id'";
    $koneksi = Connection();
    $hasil = mysqli_query($koneksi, $sql);
    if ($baris = mysqli_fetch_assoc($hasil)) {
        $data['id'] = $baris['id'];
        $data['username'] = $baris['username'];
        $data['email'] = $baris['email'];
    }
    mysqli_close($koneksi);
    return $data;
}

function updateAdminProfile($id, $username, $email) {
    $koneksi = Connection();
    
    $stmt = $koneksi->prepare("UPDATE admin SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $id);
    
    return $stmt->execute();
}

?>