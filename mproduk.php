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
		$c		= $_POST['c'];
		$d		= $_POST['d'];
		$e		= $_POST['e'];
		$f		= $_POST['f'];
		$i		= $_FILES['img']['name'];
		if($i == ""){
			echo "<script language='JavaScript'>alert('Tidak Ada Gambar Dipilih'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if($i != ""){
			$query		= "INSERT INTO `tb_produk` (
							`nama`, 
							`id_katproduk`, 
							`keterangan`, 
							`harga`, 
							`stok`, 
							`denda`
						) VALUES (
							'".$a."', '".$b."', '".$c."', '".$d."', '".$e."', '".$f."'
						)";
			$hasil		= mysqli_query($db, $query);
			$id			= mysqli_insert_id($db);
			$new_name	= $id.".jpg";
			$file		= 'img';
			$dir		= 'img/produk/';
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
		$d		= $_POST['d'];
		$e		= $_POST['e'];
		$f		= $_POST['f'];
		$g		= $_POST['g'];
		
		$query = "UPDATE `tb_produk` SET `nama` = '".$a."', `id_katproduk` = '".$b."', `keterangan` = '".$c."', `harga` = '".$d."', `stok` = '".$e."', `denda` = '".$g."' WHERE `id_produk` = '".$f."'";
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
			$dir='img/produk/';
			unlink($dir.$new_name);
			$width	= 600;
			$height	= 600;
			UploadGambar($new_name,$file,$dir,$width,$height);
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "delete"){
		$id		= $_GET['id'];
		$query	= "DELETE FROM `tb_produk` WHERE `id_produk` = '".$id."'";
		$hasil	= mysqli_query($db, $query);
		unlink("img/produk/".$_GET['id'].".jpg");
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
		<h1 class="mt-4">Manage Data Produk</h1><hr/>
		<?php
		if(isset($_GET['op'])){
			$op = $_GET['op'];
			if($op=="tambah"){
				?>
				<h2>Tambah Data Produk</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=save" enctype="multipart/form-data">
					<label>Nama Produk</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Nama Produk" maxlength="72" style="width:100%" required/><br/>
					<label>Kategori</label><br/>
					<select name="b" class="form-control" style="width:100%;" required>
						<option value="" disabled selected style="display:none;">Pilih Kategori</option>
						<?php
						$q1 = "SELECT * FROM `tb_katproduk`";
						$h1 = mysqli_query($db, $q1);
						while ($d1 = mysqli_fetch_array($h1)){
							echo "<option value=\"".$d1['id_katproduk']."\">".$d1['nama']."</option>";
						}
						?>
					</select><br/>
					<label>Keterangan Produk</label><br/>
					<textarea name="c" class="form-control" placeholder="Keterangan Produk" style="width:100%" required/></textarea><br/>
					<label>Harga (per Hari)</label><br/>
					<input type="text" name="d" class="form-control" placeholder="Harga (per Hari)" maxlength="12" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<label>Stok</label><br/>
					<input type="text" name="e" class="form-control" placeholder="Stok" maxlength="12" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<label>Denda (per Hari)</label><br/>
					<input type="text" name="f" class="form-control" placeholder="Denda (per Hari)" maxlength="12" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<label>Gambar Produk</label><br/>
					<input type="file" accept="image/JPEG" name="img"> <font color="RED">(600 x 600px)</font><br/>
					<br/>
					<input type="submit" value="SIMPAN" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="edit"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_produk` WHERE `id_produk`='".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				$query2	= "SELECT * FROM `tb_katproduk` WHERE `id_katproduk`='".$data['id_katproduk']."'";
				$hasil2	= mysqli_query($db, $query2);
				$data2	= mysqli_fetch_array($hasil2);
				?>
				<h2>Edit Data Produk</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=update">
					<input type="hidden" name="f" value="<?php echo $data['id_produk']; ?>"/>
					<label>Nama Produk</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Nama Produk" value="<?php echo $data['nama']; ?>" maxlength="72" style="width:100%" required/><br/>
					<label>Kategori</label><br/>
					<select name="b" class="form-control" style="width:100%;" required>
						<option value="<?php echo $data['id_katproduk']; ?>"><?php echo $data2['nama']; ?></option>
						<?php
						$q1 = "SELECT * FROM `tb_katproduk`";
						$h1 = mysqli_query($db, $q1);
						while ($d1 = mysqli_fetch_array($h1)){
							echo "<option value=\"".$d1['id_katproduk']."\">".$d1['nama']."</option>";
						}
						?>
					</select><br/>
					<label>Keterangan Produk</label><br/>
					<textarea name="c" class="form-control" placeholder="Keterangan Produk" style="width:100%" required/><?php echo $data['keterangan']; ?></textarea><br/>
					<label>Harga (per Hari)</label><br/>
					<input type="text" name="d" class="form-control" placeholder="Harga (per Hari)" value="<?php echo $data['harga']; ?>" maxlength="12" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<label>Stok</label><br/>
					<input type="text" name="e" class="form-control" placeholder="Stok" value="<?php echo $data['stok']; ?>" maxlength="12" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<label>Denda (per Hari)</label><br/>
					<input type="text" name="g" class="form-control" placeholder="Denda (per Hari)" value="<?php echo $data['denda']; ?>" maxlength="12" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="editpict"){
				$id		= $_GET['id'];
				?>
				<h2>Ubah Gambar Produk</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=updatepict" enctype="multipart/form-data">
					<input type="hidden" name="a" value="<?php echo $id; ?>"/>
					<label>Gambar Produk</label><br/>
					<input type="file" accept="image/JPEG" name="img"> <font color="RED">(600 x 600px)</font><br/>
					<br/>
					<input type="submit" value="UBAH" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}
		}
		?>
		<b style="font-size:16pt;">Data Produk</b> <a href="<?php echo basename(__FILE__, '.php');?>?op=tambah" style="text-decoration:none;font-size:16pt;float:right;">Tambah Data Produk</a><br/><br/>
		<table id="myTable" class="table table-striped" width="100%" style="border:1px solid #000;font-size:7pt;">
			<thead style="background:#444444;color:#fff;">
				<tr>
					<th style="border:1px solid #000;">No</th>
					<th style="border:1px solid #000;">Produk</th>
					<th style="border:1px solid #000;">Keterangan</th>
					<th style="border:1px solid #000;">Harga</th>
					<th style="border:1px solid #000;">Denda</th>
					<th style="border:1px solid #000;">Stok</th>
					<th style="border:1px solid #000;">Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = 1;
			$query = "SELECT * FROM `tb_produk`";
			$hasil = mysqli_query($db, $query);
			while ($data = mysqli_fetch_array($hasil)){
				$query2	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data['id_katproduk']."'";
				$hasil2	= mysqli_query($db, $query2);
				$data2	= mysqli_fetch_array($hasil2);
				if($data['stok'] <= 0){
					$clr = "color:RED;";
				}if($data['stok'] > 0){
					$clr = "";
				}
				echo "<tr>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$no++."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".$data['nama']."<br/><img src=\"img/produk/".$data['id_produk'].".jpg\" width=\"100px\"></td>
					<td valign=\"top\" style=\"border:1px solid #000;\">".str_replace("\r\n", "<br/>", $data['keterangan'])."</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">Rp. ".number_format($data['harga'],0,",",".").",- /hari</td>
					<td valign=\"top\" style=\"border:1px solid #000;\">Rp. ".number_format($data['denda'],0,",",".").",- /hari</td>
					<td valign=\"top\" style=\"border:1px solid #000;".$clr."\">".$data['stok']." pcs</td>
					<td valign=\"top\" align=\"center\" width=\"110px\" style=\"border:1px solid #000;\">
						<b>
						<a href=\"".basename(__FILE__, '.php')."?op=edit&id=".$data['id_produk']."\" style=\"color:#3892d6;\">Edit</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=editpict&id=".$data['id_produk']."\">Ubah Gambar</a>
						<br/>
						<a href=\"".basename(__FILE__, '.php')."?op=delete&id=".$data['id_produk']."\" style=\"color:#cd1026;\" onclick=\"return konfirmasi()\">Delete</a>
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