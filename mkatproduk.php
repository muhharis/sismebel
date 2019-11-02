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
			$query		= "INSERT INTO `tb_katproduk` (
							`subkatproduk`, 
							`nama`
						) VALUES (
							'".$b."', '".$a."'
						)";
			$hasil		= mysqli_query($db, $query);
			$id			= mysqli_insert_id($db);
			$new_name	= $id.".jpg";
			$file		= 'img';
			$dir		= 'img/kategori/';
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
		
		$query = "UPDATE `tb_katproduk` SET `subkatproduk` = '".$c."', `nama` = '".$b."' WHERE `id_katproduk` = '".$_POST['a']."'";
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
			$dir='img/kategori/';
			unlink($dir.$new_name);
			$width=600;
			$height=600;
			UploadGambar($new_name,$file,$dir,$width,$height);
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "delete"){
		$id		= $_GET['id'];
		$query	= "DELETE FROM `tb_katproduk` WHERE `id_katproduk` = '".$id."'";
		$hasil	= mysqli_query($db, $query);
		unlink("img/kategori/".$_GET['id'].".jpg");
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}
}
include "header.php";
$xpage	="mkatproduk";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Manage Data Kategori Produk</h1><hr/>
		<?php
		if(isset($_GET['op'])){
			$op = $_GET['op'];
			if($op=="tambah"){
				?>
				<h2>Tambah Data Kategori Produk</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=save" enctype="multipart/form-data">
					<label>Nama Kategori</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Nama Kategori" maxlength="72" style="width:100%" required/><br/>
					<label>Sub Kategori Dari (Optional)</label><br/>
					<select name="b" class="form-control" style="width:100%;" required>
						<option value="0">Kategori Utama</option>
						<?php
						$q1 = "SELECT * FROM `tb_katproduk`";
						$h1 = mysqli_query($db, $q1);
						while ($d1 = mysqli_fetch_array($h1)){
							echo "<option value=\"".$d1['id_katproduk']."\">".$d1['nama']."</option>";
						}
						?>
					</select><br/>
					<label>Gambar Kategori</label><br/>
					<input type="file" accept="image/JPEG" name="img"> <font color="RED">(600 x 600px)</font><br/>
					<br/>
					<input type="submit" value="SIMPAN" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="edit"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_katproduk` WHERE `id_katproduk`='".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				$query2	= "SELECT * FROM `tb_katproduk` WHERE `id_katproduk`='".$data['subkatproduk']."'";
				$hasil2	= mysqli_query($db, $query2);
				$data2	= mysqli_fetch_array($hasil2);
				?>
				<h2>Edit Data Kategori Produk</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=update">
					<input type="hidden" name="a" value="<?php echo $data['id_katproduk']; ?>"/>
					<label>Nama Kategori</label><br/>
					<input type="text" name="b" class="form-control" placeholder="Nama Kategori" value="<?php echo $data['nama']; ?>" maxlength="72" style="width:100%" required/><br/>
					<label>Sub Kategori Dari (Optional)</label><br/>
					<select name="c" class="form-control" style="width:100%;" required>
						<?php
						if($data['subkatproduk'] == 0){
							echo "<option value=\"".$data['subkatproduk']."\">Kategori Utama</option>";
							$q1 = "SELECT * FROM `tb_katproduk`";
						}if($data['subkatproduk'] != 0){
							echo "<option value=\"".$data['subkatproduk']."\">".$data2['nama']."</option>
							<option value=\"0\">Kategori Utama</option>";
							$q1 = "SELECT * FROM `tb_katproduk` WHERE `id_katproduk` <> '".$data['subkatproduk']."' AND `id_katproduk` <> '".$data['id_katproduk']."'";
						}
						$h1 = mysqli_query($db, $q1);
						while ($d1 = mysqli_fetch_array($h1)){
							echo "<option value=\"".$d1['id_katproduk']."\">".$d1['nama']."</option>";
						}
						?>
					</select><br/>
					<br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="editpict"){
				$id		= $_GET['id'];
				?>
				<h2>Ubah Gambar Kategori Produk</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=updatepict" enctype="multipart/form-data">
					<input type="hidden" name="a" value="<?php echo $id; ?>"/>
					<label>Gambar Kategori</label><br/>
					<input type="file" accept="image/JPEG" name="img"> <font color="RED">(600 x 600px)</font><br/>
					<br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}
		}
		?>
		<b style="font-size:16pt;">Data Kategori Produk</b> <a href="<?php echo basename(__FILE__, '.php');?>?op=tambah" style="text-decoration:none;font-size:16pt;float:right;">Tambah Data Kategori Produk</a><br/><br/>
		<table id="myTable" class="table table-striped" style="border:1px solid #000;">
			<thead style="background:#444444;color:#fff;">
				<tr>
					<th style="border:1px solid #000;">No</th>
					<th style="border:1px solid #000;">Nama</th>
					<th style="border:1px solid #000;">Gambar</th>
					<th style="border:1px solid #000;">Sub Kategori Produk</th>
					<th style="border:1px solid #000;">Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			$query = "SELECT * FROM `tb_katproduk`";
			$hasil = mysqli_query($db, $query);
			while ($data = mysqli_fetch_array($hasil)){
				if($data['subkatproduk'] != 0){
					$query2	= "SELECT * FROM `tb_katproduk` WHERE `id_katproduk` = '".$data['subkatproduk']."'";
					$hasil2	= mysqli_query($db, $query2);
					$data2	= mysqli_fetch_array($hasil2);
					$subkat = $data2['nama'];
				}if($data['subkatproduk'] == 0){
					$subkat = "Kategori Utama";
				}
				echo "<tr>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$no++."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['nama']."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\"><img src=\"img/kategori/".$data['id_katproduk'].".jpg\" width=\"200px\"></td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$subkat."</td>
					<td valign=\"top\" align=\"center\" width=\"150px\" style=\"border:1px solid #000;\">
						<b>
						<a href=\"".basename(__FILE__, '.php')."?op=edit&id=".$data['id_katproduk']."\" style=\"color:#3892d6;\">Edit</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=editpict&id=".$data['id_katproduk']."\">Ubah Gambar</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=delete&id=".$data['id_katproduk']."\" style=\"color:#cd1026;\" onclick=\"return konfirmasi()\">Delete</a>
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