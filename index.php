<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("tidak terkoneksi");
}

$nim = "";
$nama = "";
$alamat = "";
$fakultas = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $id = $_GET['id'];
    $sql1 = "delete from mahasiswa where id = '$id' ";
    $q1 = mysqli_query($koneksi, $sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal menghapus data";
    }
}
if ($op == "edit") {
    $id = $_GET["id"];
    $sql1 = "select * from mahasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $alamat = $r1['alamat'];
    $fakultas = $r1['fakultas'];

    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}


if (isset($_POST["simpan"])) { //untuk create
    $nim = $_POST["nim"];
    $nama = $_POST["nama"];
    $alamat = $_POST["alamat"];
    $fakultas = $_POST["fakultas"];

    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update mahasiswa set nim = '$nim', nama='$nama', alamat='$alamat', fakultas='$fakultas' where id = '$id' ";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into mahasiswa (nim, nama, alamat, fakultas) values ('$nim', '$nama', '$alamat', '$fakultas')";
            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }

    } else {
        $error = "Silahkan masukkan semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / edit data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                    <?php
                    header("refresh:3;url=index.php");//5 : detik
                }
                ?>

                <?php
                if ($sukses) {
                    ?>
                    <div class="alert alert-sukses" role="alert">
                        <?php echo $sukses ?>
                    </div>
                    <?php
                    header("refresh:3;url=index.php");//5 : detik
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                        <div class="mb-3 row">
                            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="alamat" name="alamat"
                                    value="<?php echo $alamat ?>">
                            </div>
                            <div class="mb-3 row">
                                <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="fakultas" id="fakultas">
                                        <option value="">- Pilih Fakultas - </option>
                                        <option value="saintek" <?php if ($fakultas == "saintek")
                                            echo "selected" ?>>
                                                saintek</option>
                                            <option value="soshum" <?php if ($fakultas == "soshum")
                                            echo "selected" ?>>soshum
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col12">
                                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                                    </div>
                    </form>
                </div>
                <!-- untuk mengeluarkan data -->
                <div class="card">
                    <div class="card-header text-white bg-secondary">
                        Data mahasiswa
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Fakultas</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            <tbody>
                                <?php
                                        $sql2 = "select * from mahasiswa order by id desc";
                                        $q2 = mysqli_query($koneksi, $sql2);
                                        $urut = 1;
                                        while ($r2 = mysqli_fetch_array($q2)) {
                                            $id = $r2["id"];
                                            $nim = $r2["nim"];
                                            $nama = $r2["nama"];
                                            $alamat = $r2["alamat"];
                                            $fakultas = $r2["fakultas"];

                                            ?>
                                <tr>
                                    <th scope="row">
                                        <?php echo $urut++ ?>
                                    </th>
                                    <td scope="row">
                                        <?php echo $nim ?>
                                    </td>
                                    <td scope="row">
                                        <?php echo $nama ?>
                                    </td>
                                    <td scope="row">
                                        <?php echo $alamat ?>
                                    </td>
                                    <td scope="row">
                                        <?php echo $fakultas ?>
                                    </td>
                                    <td scope="row">
                                        <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button"
                                                class="btn btn-warning">Edit</button></a>
                                        <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau hapus data ?')"><button type="button"
                                                class="btn btn-danger">Delete</button></a>

                                    
                                    </td>
                                </tr>
                                <?php
                                        }
                                        ?>
                        </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
</body>

</html>
