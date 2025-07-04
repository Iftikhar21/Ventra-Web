<?php
    require_once('../Connection/connection.php');

    function getAllBarang() {
        $data = array();
        $sql = "SELECT * FROM ventra_produk JOIN categories ON ventra_produk.Kategori = categories.id_kategori";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getAllBarangDanDetail() {
        $data = array();
        $sql = "SELECT * FROM ventra_produk JOIN categories ON ventra_produk.Kategori = categories.id_kategori JOIN ventra_produk_detail ON ventra_produk.id = ventra_produk_detail.produk_id";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getBarang($id) {
        $data = array();
        $sql = "SELECT * FROM ventra_produk JOIN categories ON ventra_produk.Kategori = categories.id_kategori WHERE id = '$id'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        if ($baris = mysqli_fetch_assoc($hasil)) {
            $data = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTotalBarang() {
        $koneksi = Connection();
        $sql = "SELECT COUNT(*) AS jumlah_kolom FROM ventra_produk";
        $result = $koneksi->query($sql);
        $jumlahBarang = $result->fetch_assoc()['jumlah_kolom'];
        return $jumlahBarang;
    }

    function addBarang($id, $namaBarang, $bahan, $harga, $gambar, $kategoriId) {
        $koneksi = Connection();

        // Cek apakah gambar diberikan atau tidak
        $photoData = null;
        if (!empty($gambar) && file_exists($gambar)) {
            $content = file_get_contents($gambar);
            $photoData = mysqli_real_escape_string($koneksi, $content);
            $photoData = "'$photoData'"; // Bungkus dalam tanda kutip jika ada isinya
        } else {
            $photoData = "NULL"; // Biarkan sebagai NULL SQL
        }

        $cek = mysqli_query($koneksi, "SELECT * FROM ventra_produk WHERE id = '$id'");

        if (mysqli_num_rows($cek) == 0) {
            $sql = "INSERT INTO ventra_produk (Nama_Brg, Bahan, harga_jual, Gambar, Kategori)
                    VALUES ('$namaBarang', '$bahan', '$harga', $photoData, '$kategoriId')";
            $hasil = mysqli_query($koneksi, $sql);
            mysqli_close($koneksi);
            return $hasil ? 1 : 0;
        }

        mysqli_close($koneksi);
        return 1; // Produk sudah ada, dianggap sukses
    }

    function editBarang($id, $namaBarang, $bahan, $harga, $gambar, $kategoriId) {
        $koneksi = Connection();
        if ($gambar && file_exists($gambar)) {
            $photoData = file_get_contents($gambar);
            $photoData = mysqli_real_escape_string($koneksi, $photoData);
            $sql = "UPDATE ventra_produk SET Nama_Brg = '$namaBarang', Bahan = '$bahan', harga_jual = '$harga', Kategori = '$kategoriId',
                    Gambar = '$photoData' WHERE id = '$id'";
        } else {
            $sql = "UPDATE ventra_produk SET Nama_Brg = '$namaBarang', Bahan = '$bahan', harga_jual = '$harga', Kategori = '$kategoriId' WHERE id = '$id'";
        }

        $hasil = mysqli_query($koneksi, $sql);
        mysqli_close($koneksi);
        return $hasil ? 1 : 0;
    }

    function deleteBarang($id) {
        $koneksi = Connection();
        
        // Cek apakah ada detail produk terkait
        $checkSql = "SELECT COUNT(*) as total FROM ventra_produk_detail WHERE produk_id = '$id'";
        $checkResult = mysqli_query($koneksi, $checkSql);
        $row = mysqli_fetch_assoc($checkResult);
        
        if ($row['total'] > 0) {
            mysqli_close($koneksi);
            return false; // Tidak bisa dihapus karena ada detail produk
        }
        
        // Jika tidak ada detail produk, lanjutkan penghapusan
        $sql = "DELETE FROM ventra_produk WHERE id = '$id'";
        $result = mysqli_query($koneksi, $sql);
        
        mysqli_close($koneksi);
        return $result;
    }

    function getDetailBarangByProduk($id) {
        $data = array();
        $sql = "SELECT * FROM ventra_produk_detail WHERE produk_id = '$id'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getDetailBarangByEdit($kodeBarang) {
        $data = array();
        $sql = "SELECT * FROM ventra_produk_detail WHERE Kode_Brg = '$kodeBarang'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getBarangMenipis() {
        $data = array();
        $sql = "SELECT p.id, p.Nama_Brg, p.Bahan, p.Gambar, p.Kategori, p.harga_jual, d.produk_id, d.Kode_Brg,
                    d.ukuran, d.pattern, d.barcode, d.stock
                FROM ventra_produk p
                JOIN ventra_produk_detail d ON p.id = d.produk_id
                WHERE d.stock <= 5";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getTotalBarangMenipis() {
        $koneksi = Connection();
        $sql = "SELECT COUNT(*) AS jumlah_kolom FROM ventra_produk_detail WHERE stock <= 5";
        $result = $koneksi->query($sql);
        $jumlah = $result->fetch_assoc()['jumlah_kolom'];
        return $jumlah;
    }

    function addDetailBarang($kodeBarang, $produk_id, $ukuran, $patternPath, $barcode, $stock) {
        $koneksi = Connection();
    
        // Escape string path (bukan file binary lagi)
        $patternPath = mysqli_real_escape_string($koneksi, $patternPath);
    
        $sql = "INSERT INTO ventra_produk_detail (Kode_Brg, produk_id, ukuran, pattern, barcode, stock)
                VALUES ('$kodeBarang', $produk_id, '$ukuran', '$patternPath', '$barcode', '$stock')";
        
        $hasil = mysqli_query($koneksi, $sql);
        mysqli_close($koneksi);
    
        return $hasil ? 1 : 0;
    }


    function updateDetailBarang($kodeBarangLama, $kodeBarangBaru, $produk_id, $ukuran, $patternPath, $barcode, $stock) {
        $koneksi = Connection();
        
        // Escape semua input teks
        $kodeBarangBaru = mysqli_real_escape_string($koneksi, $kodeBarangBaru);
        $ukuran = mysqli_real_escape_string($koneksi, $ukuran);
        $barcode = mysqli_real_escape_string($koneksi, $barcode);
        $stock = mysqli_real_escape_string($koneksi, $stock);
        $patternPath = $patternPath !== null ? mysqli_real_escape_string($koneksi, $patternPath) : null;
    
        // Query dasar
        $sql = "UPDATE ventra_produk_detail SET 
                Kode_Brg = '$kodeBarangBaru',
                produk_id = '$produk_id', 
                ukuran = '$ukuran', 
                barcode = '$barcode', 
                stock = '$stock'";
    
        // Tambahkan update path pattern jika ada file baru
        if ($patternPath !== null) {
            $sql .= ", pattern = '$patternPath'";
        }
    
        // WHERE untuk cari berdasarkan kode lama
        $sql .= " WHERE Kode_Brg = '$kodeBarangLama'";
    
        $result = mysqli_query($koneksi, $sql);
        mysqli_close($koneksi);
        return $result ? 1 : 0;
    }



    function deleteDetailBarangByProduk($kodeBarang) {
        $koneksi = Connection();
        $sql = "DELETE FROM ventra_produk_detail WHERE Kode_Brg = '$kodeBarang'";
        $hasil = mysqli_query($koneksi, $sql);
        mysqli_close($koneksi);
        return $hasil ? 1 : 0;
    }

    function getBarangByEvent($idEvent) {
        $data = array();
        $koneksi = Connection();
        
        $sql = "SELECT 
                    vp.id as id_barang,
                    vp.Nama_Brg as nama_barang,
                    vp.harga_jual as harga,
                    vp.Gambar as gambar,
                    c.nama_kategori as kategori
                FROM ventra_produk vp
                JOIN categories c ON vp.Kategori = c.id_kategori
                JOIN ventra_detail_event vde ON vp.id = vde.id_produk
                WHERE vde.id_event = '$idEvent'";
        
        $hasil = mysqli_query($koneksi, $sql);
        
        if ($hasil) {
            while ($baris = mysqli_fetch_assoc($hasil)) {
                $data[] = $baris;
            }
        }
        
        mysqli_close($koneksi);
        return $data;
    }

    function getUkuranWhereID($id) {
        $data = array();
        $sql = "SELECT DISTINCT ukuran FROM ventra_produk_detail WHERE produk_id = '$id'";
        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = $baris;
        }
        mysqli_close($koneksi);
        return $data;
    }

?>
