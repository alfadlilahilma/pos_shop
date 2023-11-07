<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pos_shopdela";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$id_product     = "";
$nama    	    = "";
$harga          = "";
$stock          = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if($op == 'delete'){
    $id_product         = $_GET['id_product'];
    $sql1       = "delete from pos_shopdela where id = '$id_product'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from product where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $nama       = $r1['nama'];
    $harga = $r1['harga'];
    $stock = $r1['stock'];

    if ($name == '') {
        $error = "Data tidak ditemuukan";
    }
}


if (isset($_POST['simpan'])) { //untuk create
    $name           = $_POST['name'];
    $description    = $_POST['description'];


    if ($name && $description) { //untuk insert
        if ($op == 'edit') { //untuk update
            $sql1       = "update pos_shopdela set name = '$name',description='$description' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else {     //untuk insert
            $sql1   = "insert into pos_shopdela(nama, harga, stock) values ('$nama', '$description' , 'harga', 'stock')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
            }
        }
    }
}

?>