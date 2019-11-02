<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
if(isset($_GET['op'])){
	$op	= $_GET['op'];
	if($op == "save"){
		$a		= $_POST['a'];
		$b		= $_POST['b'];
		$c		= md5($_POST['c']);
		$d		= $_POST['d'];
		$e		= $_POST['e'];
		$f		= $_POST['f'];
		$ra1 = mysqli_query($db, "SELECT * FROM `tb_login` WHERE `username` ='".$a."'");
		$na1 = mysqli_num_rows($ra1);
		if($na1 == 1){
			echo "<script language='JavaScript'>alert('Username Sudah Ada, Silahkan Gunakan Username Yang Lain'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if($na1 == 0){
			$query = "INSERT INTO `tb_login` (
						`username`, 
						`nama`, 
						`password`, 
						`alamat`, 
						`notelp`, 
						`level`
					) VALUES (
						'".$a."', '".$b."', '".$c."', '".$d."', '".$f."', 'admin'
					)";
			$hasil = mysqli_query($db, $query);
			if ($hasil){
				echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
			}if (!$hasil){
				echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
			}
		}
	}if($op == "update"){
		$query1	= "SELECT * FROM `tb_login` WHERE `username` = '".$_POST['a']."'";
		$hasil1	= mysqli_query($db, $query1);
		$data1 	= mysqli_fetch_array($hasil1);
		$b		= $_POST['b'];
		if($_POST['c'] != ""){
			$c	= md5($_POST['b']);
		}if($_POST['c'] == ""){
			$c	= $data1['password'];
		}
		$d		= $_POST['d'];
		$e		= $_POST['e'];
		
		$query = "UPDATE `tb_login` SET `nama` = '".$b."', `password` = '".$c."', `alamat` = '".$d."', `notelp` = '".$e."' WHERE `username` = '".$_POST['a']."'";
		$hasil = mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "delete"){
		$id		= $_GET['id'];
		$query	= "DELETE FROM `tb_login` WHERE `username` = '".$id."'";
		$hasil	= mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}
}
include "header.php";
$xpage	="madmin";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Manage Data Admin</h1><hr/>
		<?php
		if(isset($_GET['op'])){
			$op = $_GET['op'];
			if($op=="tambah"){
				?>
				<h2>Tambah Data Admin</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=save">
					<input type="hidden" name="e" value="1" required/>
					<label>Username</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Username" maxlength="15" style="width:100%" required/><br/>
					<label>Nama</label><br/>
					<input type="text" name="b" class="form-control" placeholder="Nama" maxlength="72" style="width:100%" required/><br/>
					<label>Password</label><br/>
					<input type="password" name="c" class="form-control" placeholder="Password" maxlength="72" style="width:100%" required/><br/>
					<label>Alamat</label><br/>
					<input type="text" name="d" class="form-control" placeholder="Alamat" maxlength="160" style="width:100%" required/><br/>
					<label>No. Telp</label><br/>
					<input type="text" name="f" class="form-control" placeholder="No. Telp" maxlength="15" style="width:100%" required/><br/><br/>
					<input type="submit" value="SIMPAN" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="edit"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_login` WHERE `username`='".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				?>
				<h2>Edit Data Admin</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=update">
					<input type="hidden" name="a" value="<?php echo $data['username']; ?>"/>
					<label>Username</label><br/>
					<input type="text" class="form-control" value="<?php echo $data['username']; ?>" style="width:100%" disabled/><br/>
					<label>Nama</label><br/>
					<input type="text" name="b" class="form-control" placeholder="Nama" value="<?php echo $data['nama']; ?>" maxlength="72" style="width:100%" required/><br/>
					<label>Password</label><br/>
					<input type="password" name="c" class="form-control" placeholder="Password (Kosongi Jika Tidak Diubah)" maxlength="72" style="width:100%"/><br/>
					<label>Alamat</label><br/>
					<input type="text" name="d" class="form-control" placeholder="Alamat" value="<?php echo $data['alamat']; ?>" maxlength="160" style="width:100%" required/><br/>
					<label>No. Telp</label><br/>
					<input type="text" name="e" class="form-control" placeholder="No. Telp" value="<?php echo $data['notelp']; ?>" maxlength="15" style="width:100%" required/><br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}
		}
		?>
		<b style="font-size:16pt;">Data Admin</b> <a href="<?php echo basename(__FILE__, '.php');?>?op=tambah" style="text-decoration:none;font-size:16pt;float:right;">Tambah Data Admin</a><br/><br/>
		<table id="myTable" class="table table-striped" style="border:1px solid #000;">
			<thead style="background:#444444;color:#fff;">
				<tr>
					<th style="border:1px solid #000;">Username</th>
					<th style="border:1px solid #000;">Nama</th>
					<th style="border:1px solid #000;">Alamat</th>
					<th style="border:1px solid #000;">Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$query = "SELECT * FROM `tb_login` WHERE `level` = 'admin'";
			$hasil = mysqli_query($db, $query);
			while ($data = mysqli_fetch_array($hasil)){
				echo "<tr>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['username']."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['nama']."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['alamat']."</td>
					<td valign=\"top\" align=\"center\" width=\"100px\" style=\"border:1px solid #000;\">
						<b>
						<a href=\"".basename(__FILE__, '.php')."?op=edit&id=".$data['username']."\" style=\"color:#3892d6;\">Edit</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=delete&id=".$data['username']."\" style=\"color:#cd1026;\" onclick=\"return konfirmasi()\">Delete</a>
						</b>
					</td>
				</tr>";
			}
			?>
			</tbody>
		</table>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>