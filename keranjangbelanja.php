<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
if(isset($_GET['op'])){
	$op	= $_GET['op'];
	if($op == "delete"){
		$id		= $_GET['id'];
		$query	= "DELETE FROM `tb_detailpenyewaan` WHERE `id_detailpenyewaan` = '".$id."'";
		$hasil	= mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "checkout"){
		$stot	= 0;
		$tot	= 0;
		$a		= $_POST['a'];
		$b		= $_POST['b'];
		$c		= $_POST['c'];
		$query = "SELECT * FROM `tb_detailpenyewaan` WHERE `username` = '".$a."' AND `status` = 'Keranjang Belanja' AND `id_penyewaan` = '0'";
		$hasil = mysqli_query($db, $query);
		while ($data = mysqli_fetch_array($hasil)){
			$query2	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data['id_produk']."'";
			$hasil2	= mysqli_query($db, $query2);
			$data2	= mysqli_fetch_array($hasil2);
			$hrg1	= (int)$data2['harga'] * (int)$data['qty'];
			$hrg2	= $hrg1 * (int)$c;
			$tot	= $tot + (int)$hrg2;
			mysqli_query($db, "UPDATE `tb_detailpenyewaan` SET `status` = 'Transaksi', `harga` = '".$data2['harga']."', `lamasewa` = '".$c."', `subtotal` = '".$hrg2."' WHERE `id_detailpenyewaan` = '".$data['id_detailpenyewaan']."'");
			$stot	= $stot + $hrg1;
		}
		$query	= "INSERT INTO `tb_penyewaan` (
					`username`, 
					`waktuorder`, 
					`waktutempo`, 
					`tglsewa`, 
					`lamasewa`, 
					`tglkembali`, 
					`subtotal`, 
					`total`, 
					`status`
				) VALUES (
					'".$a."', '".date('H:i:s d-m-Y')."', '".date('H:i:s d-m-Y', strtotime("+5 hours"))."', '".$b."', '".$c."', '".date('d-m-Y',strtotime($b . "+".(int)$c." days"))."', '".$stot."', '".$tot."', 'Belum Terbayar'
				)";
		$hasil	= mysqli_query($db, $query);
		$idtrx	= mysqli_insert_id($db);
		$query2	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '0' AND `status` = 'Transaksi' AND `username` = '".$a."'";
		$hasil2	= mysqli_query($db, $query2);
		while ($data2 = mysqli_fetch_array($hasil2)){
			$query	= "UPDATE `tb_detailpenyewaan` SET `id_penyewaan` = '".$idtrx."' WHERE `id_detailpenyewaan` = '".$data2['id_detailpenyewaan']."'";
			$hasil	= mysqli_query($db, $query);
		}
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = 'dpenyewaan';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}
}
include "header.php";
$xpage	="keranjangbelanja";
?>
<div class="row">
	<div class="col-lg-8">
		<?php
		if(!isset($_GET['op'])){
			echo "<h1 class=\"mt-4\">Keranjang Belanja Anda</h1><hr/>";
		}if(isset($_GET['op'])){
			$op	= $_GET['op'];
			if($op == "checkout1"){
				echo "<h1 class=\"mt-4\">Checkout Penyewaan</h1><hr/>";
			}
		}
		$query = "SELECT * FROM `tb_detailpenyewaan` WHERE `username` = '".$_SESSION['username']."' AND `status` = 'Keranjang Belanja'";
		$hasil = mysqli_query($db, $query);
		if(mysqli_num_rows($hasil) < 1){
			echo "<center><h3>TIDAK ADA DATA</h3></center>";
		}if(mysqli_num_rows($hasil) >= 1){
			?>
			<table class="table table-striped" width="100%" style="border:1px solid #000;font-size:8pt;">
				<thead style="background:#444444;color:#fff;">
					<tr>
						<th style="border:1px solid #000;">Produk</th>
						<th style="border:1px solid #000;">Jumlah Sewa</th>
						<th style="border:1px solid #000;">Harga</th>
						<?php
						if(!isset($_GET['op'])){
							echo "<th style=\"border:1px solid #000;\"></th>";
						}
						?>
					</tr>
				</thead>
				<tbody>
				<?php
				$hrg	= 0;
				$query3	= "SELECT * FROM `tb_login` WHERE `username` = '".$_SESSION['username']."'";
				$hasil3	= mysqli_query($db, $query3);
				$data3	= mysqli_fetch_array($hasil3);
				while ($data = mysqli_fetch_array($hasil)){
					$query2	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data['id_produk']."'";
					$hasil2	= mysqli_query($db, $query2);
					$data2	= mysqli_fetch_array($hasil2);
					echo "<tr>
						<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data2['nama']."</b><br/>Harga@ : <b>".number_format($data2['harga'],0,",",".").",- /hari</b></td>
						<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data['qty']."</b></td>
						<td valign=\"top\" style=\"border:1px solid #000;\"><b>".number_format(($data2['harga'] * $data['qty']),0,",",".").",- /hari</b></td>";
						if(!isset($_GET['op'])){
							echo "<td valign=\"top\" style=\"border:1px solid #000;\">
								<b>
								<a href=\"produk?id_produk=".$data['id_produk']."\" style=\"color:BLUE;font-size:10pt;\">[<b style=\"font-size:13pt;\">+</b>]</a>&nbsp;&nbsp; 
								<a href=\"".basename(__FILE__, '.php')."?op=delete&id=".$data['id_detailpenyewaan']."\" style=\"color:#cd1026;font-size:10pt;\" onclick=\"return konfirmasi()\">[X]</a>
								</b>
							</td>";
						}
					echo "</tr>";
				}
				?>
				</tbody>
			</table>
		<center>
		<?php
		if(!isset($_GET['op'])){
			?>
			<a href="produk" class="btn btn-secondary">PESAN LAGI</a><br/><hr/>
			<a href="<?php echo basename(__FILE__, '.php')."?op=checkout1&a=".$_SESSION['username']; ?>" class="btn btn-secondary">Selesaikan Pemesanan Sewa (Checkout)</a>
			<?php
		}if(isset($_GET['op'])){
			$op	= $_GET['op'];
			if($op == "checkout1"){
			?>
			<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=checkout">
				<input type="hidden" name="a" value="<?php echo $_SESSION['username']; ?>"/>
				<label>Tanggal Sewa</label>
				<input type="text" name="b" id="tanggal1" class="form-control" placeholder="Tanggal Sewa" maxlength="10" style="width:50%" required/><br/>
				<label>Lama Sewa (Hari)</label>
				<input type="text" name="c" class="form-control" placeholder="Lama Sewa (Hari)" maxlength="3" style="width:50%" onkeypress="return isNumberKey(event)" required/><br/>
				<br/>
				<input type="submit" value="CHECKOUT" class="btn btn-secondary" style="width:100%"/>
			</form>
			<?php
			}
		}
		?>
		</center>
		<?php
		}
		?>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>