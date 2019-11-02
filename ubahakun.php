<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
if(isset($_GET['op'])){
	$op	= $_GET['op'];
	if($op == "update"){
		$query1	= "SELECT * FROM `tb_login` WHERE `username` = '".$_SESSION['username']."'";
		$hasil1	= mysqli_query($db, $query1);
		$data1 	= mysqli_fetch_array($hasil1);
		$a		= $_POST['a'];
		if($_POST['b'] != ""){
			$b	= md5($_POST['b']);
		}if($_POST['b'] == ""){
			$b	= $data1['password'];
		}
		$c		= $_POST['c'];
		$d		= $_POST['d'];
		
		$query = "UPDATE `tb_login` SET `nama` = '".$a."', `password` = '".$b."', `alamat` = '".$c."', `notelp` = '".$d."' WHERE `username` = '".$_SESSION['username']."'";
		$hasil = mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}
}
include "header.php";
$xpage	= "ubahakun";
$query	= "SELECT * FROM `tb_login` WHERE `username` = '".$_SESSION['username']."'";
$hasil	= mysqli_query($db, $query);
$data	= mysqli_fetch_array($hasil);
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Ubah Akun</h1><hr/>
		<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=update">
			<label>Username</label><br/>
			<input type="text" class="form-control" value="<?php echo $data['username']; ?>" style="width:100%" disabled/><br/>
			<label>Nama</label><br/>
			<input type="text" name="a" class="form-control" placeholder="Nama" value="<?php echo $data['nama']; ?>" maxlength="72" style="width:100%" required/><br/>
			<label>Password (Kosongi Jika Tidak Diubah)</label><br/>
			<input type="password" name="b" class="form-control" placeholder="Password (Kosongi Jika Tidak Diubah)" maxlength="72" style="width:100%"/><br/>
			<label>Alamat</label><br/>
			<input type="text" name="c" class="form-control" placeholder="Alamat" value="<?php echo $data['alamat']; ?>" maxlength="160" style="width:100%" required/><br/>
			<label>No. Telp</label><br/>
			<input type="text" name="d" class="form-control" placeholder="No. Telp" value="<?php echo $data['notelp']; ?>" maxlength="15" style="width:100%" required/><br/>
			<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/><hr/>
		</form>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>