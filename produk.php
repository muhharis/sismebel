<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
if(isset($_GET['op'])){
	$op	= $_GET['op'];
	if($op == "sewa"){
		$a		= $_POST['a'];
		$b		= $_POST['b'];
		$c		= $_POST['c'];
		$qx		= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_produk` = '".$b."' AND `username` = '".$a."' AND `status` = 'Keranjang Belanja'";
		$hx		= mysqli_query($db, $qx);
		if(mysqli_num_rows($hx) == 1){
			$dx		= mysqli_fetch_array($hx);
			$qtyu	= $dx['qty'] + $_POST['c'];
			$query	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$b."'";
			$hasil	= mysqli_query($db, $query);
			$data	= mysqli_fetch_array($hasil);
			if($data['stok'] < $qtyu){
				echo "<script language='JavaScript'>alert('Stok Kurang Dari Pemsewaan'); window.location = 'keranjangbelanja';</script>";
			}if($data['stok'] >= $qtyu){
				$query	= "UPDATE `tb_detailpenyewaan` SET `qty` = '".$qtyu."' WHERE `id_detailpenyewaan` = '".$dx['id_detailpenyewaan']."'";
				$hasil	= mysqli_query($db, $query);
				echo "<script language='JavaScript'>alert('Berhasil Masuk Keranjang Belanja'); window.location = 'keranjangbelanja';</script>";
			}
		}if(mysqli_num_rows($hx) == 0){
			$query	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$b."'";
			$hasil	= mysqli_query($db, $query);
			$data	= mysqli_fetch_array($hasil);
			if($data['stok'] < $_POST['c']){
				echo "<script language='JavaScript'>alert('Stok Kurang Dari Pemsewaan'); window.location = 'keranjangbelanja';</script>";
			}if($data['stok'] >= $_POST['c']){
				$query	= "INSERT INTO `tb_detailpenyewaan` (
							`username`, 
							`id_penyewaan`, 
							`id_produk`, 
							`qty`, 
							`status`
						) VALUES (
							 '".$a."', '0', '".$b."', '".$c."', 'Keranjang Belanja'
						)";
				$hasil	= mysqli_query($db, $query);
				echo "<script language='JavaScript'>alert('Berhasil Masuk Keranjang Belanja'); window.location = 'keranjangbelanja';</script>";
			}
		}
	}
}
include "header.php";
$xpage	="produk";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Produk</h1><hr/>
		<?php
		if(isset($_GET['id_produk'])){
			$id_produk	= $_GET['id_produk'];
			$query		= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$id_produk."'";
			$hasil		= mysqli_query($db, $query);
			$data		= mysqli_fetch_array($hasil);
			echo "<center><h2>".$data['nama']."</h2></center><hr/>";
				?>
				<table width="100%" cellpadding="5px">
					<tr>
						<td valign="top" align="center" width="100%"><img src="img/produk/<?php echo $data['id_produk'];?>.jpg" width="80%"><br/><br/>
						<?php
							echo "Harga Sewa : <b>Rp. ".number_format($data['harga'],0,",",".").",- /hari</b><br/>Denda : <b>Rp. ".number_format($data['denda'],0,",",".").",- /hari</b>";
						?>
						<br/><p><?php echo str_replace("\r\n", "<br/>", $data['keterangan']);?><br/>Stok Tersedia : <?php echo $data['stok'];?> pcs</p>
						<?php
						if(isset($_SESSION['username']) AND $_SESSION['level'] == "user"){
							if($data['stok'] > 0){
								?>
								<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=sewa">
									<input type="hidden" name="a" value="<?php echo $_SESSION['username']; ?>"/>
									<input type="hidden" name="b" value="<?php echo $data['id_produk']; ?>"/><hr/>
									<label>Jumlah Sewa</label>
									<input type="text" name="c" class="form-control" placeholder="Jumlah Sewa" maxlength="8" style="width:50%" onkeypress="return isNumberKey(event)" required/><br/>
									<br/>
									<input type="submit" value="MASUK KERANJANG BELANJA" class="btn btn-secondary" style="width:100%"/>
								</form>
								<?php
							}
						}
						?>
						</td>
					</tr>
				</table><hr/>
			<br/><br/><hr style="border:2px solid #000;"/><br/><br/><br/>
			<?php
		}if(isset($_GET['id_katproduk'])){
			$id_katproduk	= $_GET['id_katproduk'];
			echo "<h2>Pilih Produk</h2><hr/>";
			$query = "SELECT * FROM `tb_produk` WHERE `id_katproduk` = '".$id_katproduk."' ORDER BY `id_produk` ASC";
			$hasil = mysqli_query($db, $query);
			while ($data = mysqli_fetch_array($hasil)){
				?>
				<a href="<?php echo basename(__FILE__, '.php');?>?id_produk=<?php echo $data['id_produk'];?>" style="color:#000;">
					<table width="100%" cellpadding="5px">
						<tr>
							<td valign="top" align="center" width="100%"><img src="img/produk/<?php echo $data['id_produk'];?>.jpg" width="60%"><br/><h3><?php echo $data['nama'];?><br/><?php echo "Rp. ".number_format($data['harga'],0,",",".").",-";?></h3><input type="submit" value="PILIH" class="btn btn-secondary" style="width:70%"/></td>
						</tr>
					</table>
				</a><hr/>
				<?php
			}
			?>
			<br/><br/><hr style="border:2px solid #000;"/><br/><br/><br/>
			<?php
		}
		echo "<h2>Pilih Kategori</h2><hr/>";
		$query = "SELECT * FROM `tb_katproduk` ORDER BY `id_katproduk` ASC";
		$hasil = mysqli_query($db, $query);
		while ($data = mysqli_fetch_array($hasil)){
			?>
			<a href="<?php echo basename(__FILE__, '.php');?>?id_katproduk=<?php echo $data['id_katproduk'];?>" style="color:#000;">
				<center><h2><?php echo $data['nama'];?></h2>
				<img src="img/kategori/<?php echo $data['id_katproduk'];?>.jpg" width="50%"></center>
			</a><hr/>
			<?php
		}
		?>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>