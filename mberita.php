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
		$i		= $_FILES['img']['name'];
		if($i == ""){
			echo "<script language='JavaScript'>alert('Tidak Ada Gambar Dipilih'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if($i != ""){
			$query		= "INSERT INTO `tb_berita` (
							`judul`, 
							`isi`, 
							`waktu`
						) VALUES (
							'".$a."', '".$b."', '".$xtgl." ".$xjam."'
						)";
			$hasil		= mysqli_query($db, $query);
			$id			= mysqli_insert_id($db);
			$new_name	= $id.".jpg";
			$file		= 'img';
			$dir		= 'img/berita/';
			$width		= 600;
			$height		= 600;
			UploadGambar($new_name,$file,$dir,$width,$height);
			if ($hasil){
				echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
			}if (!$hasil){
				echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
			}
		}
	}if($op == "update"){
		$a		= $_POST['a'];
		$b		= $_POST['b'];
		$c		= $_POST['c'];
		
		$query = "UPDATE `tb_berita` SET `judul` = '".$a."', `isi` = '".$b."' WHERE `id_berita` = '".$c."'";
		$hasil = mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "updatepict"){
		$a		= $_POST['a'];
		$i		= $_FILES['img']['name'];
		if($i == ""){
			echo "<script language='JavaScript'>alert('Tidak Ada Gambar Dipilih'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if($i != ""){
			$new_name=$_POST['a'].".jpg";
			$file='img';
			$dir='img/berita/';
			unlink($dir.$new_name);
			$width	= 600;
			$height	= 600;
			UploadGambar($new_name,$file,$dir,$width,$height);
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "delete"){
		$id		= $_GET['id'];
		$query	= "DELETE FROM `tb_berita` WHERE `id_berita` = '".$id."'";
		$hasil	= mysqli_query($db, $query);
		unlink("img/berita/".$_GET['id'].".jpg");
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}
}
include "header.php";
$xpage	="mproduk";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Manage Data Berita</h1><hr/>
		<?php
		if(isset($_GET['op'])){
			$op = $_GET['op'];
			if($op=="tambah"){
				?>
				<h2>Tambah Data Berita</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=save" enctype="multipart/form-data">
					<label>Judul Berita</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Judul Berita" maxlength="72" style="width:100%" required/><br/>
					<label>Isi Berita</label><br/>
					<textarea name="b" class="form-control" placeholder="Keterangan Berita" style="width:100%" required/></textarea><br/>
					<label>Gambar Berita</label><br/>
					<input type="file" accept="image/JPEG" name="img"> <font color="RED">(600 x 600px)</font><br/>
					<br/>
					<input type="submit" value="SIMPAN" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="edit"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_berita` WHERE `id_berita`='".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				?>
				<h2>Edit Data Berita</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=update">
					<input type="hidden" name="c" value="<?php echo $data['id_berita']; ?>"/>
					<label>Judul Berita</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Nama Berita" value="<?php echo $data['judul']; ?>" maxlength="72" style="width:100%" required/><br/>
					<label>Isi Berita</label><br/>
					<textarea name="b" class="form-control" placeholder="Isi Berita" style="width:100%" required/><?php echo $data['isi']; ?></textarea><br/>
					<br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="editpict"){
				$id		= $_GET['id'];
				?>
				<h2>Ubah Gambar Berita</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=updatepict" enctype="multipart/form-data">
					<input type="hidden" name="a" value="<?php echo $id; ?>"/>
					<label>Gambar Berita</label><br/>
					<input type="file" accept="image/JPEG" name="img"> <font color="RED">(600 x 600px)</font><br/>
					<br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}
		}
		?>
		<b style="font-size:16pt;">Data Berita</b> <a href="<?php echo basename(__FILE__, '.php');?>?op=tambah" style="text-decoration:none;font-size:16pt;float:right;">Tambah Data Berita</a><br/><br/>
		<table id="myTable" class="table table-striped" style="border:1px solid #000;">
			<thead style="background:#444444;color:#fff;">
				<tr>
					<th style="border:1px solid #000;">Waktu</th>
					<th style="border:1px solid #000;">Judul</th>
					<th style="border:1px solid #000;">Isi</th>
					<th style="border:1px solid #000;">Gambar</th>
					<th style="border:1px solid #000;">Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$query = "SELECT * FROM `tb_berita`";
			$hasil = mysqli_query($db, $query);
			while ($data = mysqli_fetch_array($hasil)){
				echo "<tr>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['waktu']."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['judul']."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".substr(str_replace("\r\n", "<br/>",$data['isi']), 0, 300)."...</td>
					<td valign=\"top\" style=\"border:1px solid #000;\"><img src=\"img/berita/".$data['id_berita'].".jpg\" width=\"100px\"></td>
					<td valign=\"top\" align=\"center\" width=\"110px\" style=\"border:1px solid #000;\">
						<b>
						<a href=\"".basename(__FILE__, '.php')."?op=edit&id=".$data['id_berita']."\" style=\"color:#3892d6;\">Edit</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=editpict&id=".$data['id_berita']."\">Ubah Gambar</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=delete&id=".$data['id_berita']."\" style=\"color:#cd1026;\" onclick=\"return konfirmasi()\">Delete</a>
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