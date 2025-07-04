<?php
    date_default_timezone_set('Asia/Jakarta');
    require_once ('../Connection/connection.php');

    function getAllLaporan() {
        $data = array();
        $tanggal = date('Y-m-d');
        $sql = "SELECT 
                    vt.*,
                    vdt.*,
                    vdb.*,
                    vp.Nama_Brg AS nama_produk,
                    vp.harga_jual AS harga_satuan
                FROM 
                    ventra_transaksi vt
                JOIN 
                    ventra_detail_transaksi vdt ON vt.ID_Transaksi = vdt.id_transaksi
                JOIN 
                    ventra_produk_detail vdb ON vdt.kode_barang = vdb.barcode
                JOIN 
                    ventra_produk vp ON vdb.produk_id = vp.id
                WHERE 
                    DATE(vt.tanggal_transaksi) = '$tanggal'"; // fix: tanggal di kolom!

        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)){
            $data[$i]['ID_Transaksi'] = $baris['ID_Transaksi'];
            $data[$i]['Total'] = $baris['Total'];
            $data[$i]['Payment'] = $baris['Payment'];
            $data[$i]['Kasir'] = $baris['Kasir'];
            $data[$i]['uang_dibayar'] = $baris['uang_dibayar'];
            $data[$i]['no_rek'] = $baris['no_rek'];
            $data[$i]['tanggal_transaksi'] = $baris['tanggal_transaksi'];
            $data[$i]['kode_barang'] = $baris['kode_barang'];
            $data[$i]['JMLH'] = $baris['JMLH'];
            $data[$i]['harga'] = $baris['harga'];
            $data[$i]['total_harga'] = $baris['total_harga'];
            $data[$i]['nama_produk'] = $baris['nama_produk'];
            $data[$i]['harga_satuan'] = $baris['harga_satuan'];
            $data[$i]['stock'] = $baris['stock'];
            $i++;
        }
        mysqli_close($koneksi);
        return $data;
    }

    function getRekapPembayaranHariIni() {
        $data = array();
        $tanggal = date('Y-m-d');
        
        $sql = "SELECT Payment AS metode, 
                        SUM(uang_dibayar) AS total,
                        COUNT(*) AS jumlah_transaksi
                FROM ventra_transaksi
                WHERE DATE(tanggal_transaksi) = '$tanggal'
                GROUP BY Payment
                ORDER BY total DESC";

        $koneksi = Connection();
        $hasil = mysqli_query($koneksi, $sql);

        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[$i]['metode'] = $baris['metode'];
            $data[$i]['total'] = $baris['total'];
            $data[$i]['jumlah_transaksi'] = $baris['jumlah_transaksi'];
            $i++;
        }

        mysqli_close($koneksi);
        return $data;
    }

    function getLaporanByTanggal($tanggal) {
        $data = array();

        $koneksi = Connection();
        $tanggal = mysqli_real_escape_string($koneksi, $tanggal);
        
        $sql = "SELECT 
                    vt.*,
                    vdt.*,
                    vdb.*,
                    vp.Nama_Brg AS nama_produk,
                    vdt.harga AS harga_satuan,
                    SUM(vdt.JMLH) AS total_barang_terjual
                FROM 
                    ventra_transaksi vt
                JOIN 
                    ventra_detail_transaksi vdt ON vt.ID_Transaksi = vdt.id_transaksi
                JOIN 
                    ventra_produk_detail vdb ON vdt.kode_barang = vdb.barcode
                JOIN 
                    ventra_produk vp ON vdb.produk_id = vp.id
                WHERE 
                    DATE(vt.tanggal_transaksi) = '$tanggal'
                GROUP BY 
                    vdb.Kode_Brg";


        $hasil = mysqli_query($koneksi, $sql);
        $i = 0;
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[$i]['ID_Transaksi'] = $baris['ID_Transaksi'];
            $data[$i]['Total'] = $baris['Total'];
            $data[$i]['Payment'] = $baris['Payment'];
            $data[$i]['Kasir'] = $baris['Kasir'];
            $data[$i]['uang_dibayar'] = $baris['uang_dibayar'];
            $data[$i]['no_rek'] = $baris['no_rek'];
            $data[$i]['tanggal_transaksi'] = $baris['tanggal_transaksi'];
            $data[$i]['kode_barang'] = $baris['kode_barang'];
            $data[$i]['JMLH'] = $baris['total_barang_terjual'];
            $data[$i]['harga'] = $baris['harga'];
            $data[$i]['total_harga'] = $baris['total_harga'];
            $data[$i]['nama_produk'] = $baris['nama_produk'];
            $data[$i]['harga_satuan'] = $baris['harga_satuan'];
            $data[$i]['stock'] = $baris['stock'];
            $data[$i]['ukuran'] = $baris['ukuran'];
            $i++;
        }

        mysqli_close($koneksi);
        return $data;
    }

    function getRekapPembayaranByTanggal($tanggal) {
        $data = array();
        $koneksi = Connection();
        $tanggal = mysqli_real_escape_string($koneksi, $tanggal);
    
        $sql = "SELECT 
                    vt.Payment AS metode,
                    SUM(vt.uang_dibayar) AS total,
                    COUNT(DISTINCT vt.ID_Transaksi) AS jumlah_transaksi
                FROM ventra_transaksi vt
                WHERE DATE(vt.tanggal_transaksi) = '$tanggal'
                GROUP BY vt.Payment
                ORDER BY total DESC";
    
        $hasil = mysqli_query($koneksi, $sql);
        while ($baris = mysqli_fetch_assoc($hasil)) {
            $data[] = [
                'metode' => $baris['metode'],
                'total' => $baris['total'],
                'jumlah_transaksi' => $baris['jumlah_transaksi']
            ];
        }
        mysqli_close($koneksi);
        return $data;
    }

?>
