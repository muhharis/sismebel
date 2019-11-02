<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
if(isset($_GET['op'])){
	$op	= $_GET['op'];
	if($op == "konfirmbayar"){
		$a		= $_POST['a'];
		$b		= $_POST['b'];
		$c		= $_POST['c'];
		$d		= $_POST['d'];
		$e		= $_POST['e'];
		$i		= $_FILES['img']['name'];
		if($i == ""){
			echo "<script language='JavaScript'>alert('Tidak Ada Gambar Dipilih'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if($i != ""){
			if($_POST['d'] < $_POST['e']){
				echo "<script language='JavaScript'>alert('Nominal Transfer Salah atau Kurang Dari Total Tagihan'); window.location = '".basename(__FILE__, '.php')."';</script>";
			}if($_POST['d'] >= $_POST['e']){
				$query = "UPDATE `tb_penyewaan` SET `bank` = '".$b."', `atasnama` = '".$c."', `jumlahtransfer` = '".$d."', `status` = 'Konfirmasi Bayar' WHERE `id_penyewaan` = '".$a."'";
				$hasil = mysqli_query($db, $query);
				$new_name	= $_POST['a'].".jpg";
				$file		= 'img';
				$dir		= 'img/bukti/';
				$width		= 800;
				$height		= 800;
				UploadGambar($new_name,$file,$dir,$width,$height);
				if ($hasil){
					echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
				}if (!$hasil){
					echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
				}
			}
		}
	}if($op == "konfirmbayar2"){
		$a		= $_POST['a'];
		$b		= $_POST['b'];
		$query1	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$a."'";
		$hasil1 = mysqli_query($db, $query1);
		while ($data1 = mysqli_fetch_array($hasil1)){
			$query1a	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data1['id_produk']."'";
			$hasil1a = mysqli_query($db, $query1a);
			$data1a = mysqli_fetch_array($hasil1a);
			$query = "UPDATE `tb_produk` SET `stok` = '".($data1a['stok'] - $data1['qty'])."' WHERE `id_produk` = '".$data1['id_produk']."'";
			$hasil = mysqli_query($db, $query);
		}
		$query = "UPDATE `tb_penyewaan` SET `status` = 'Penyewaan', `keterangan` = '".$b."' WHERE `id_penyewaan` = '".$a."'";
		$hasil = mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "cancel"){
		$id		= $_GET['id'];
		$a		= $_POST['a'];
		$query = "UPDATE `tb_penyewaan` SET `status` = 'Dibatalkan', `keterangan` = '".$a."' WHERE `id_penyewaan` = '".$id."'";
		$hasil = mysqli_query($db, $query);
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}if($op == "kembali"){
		$id		= $_GET['id'];
		$query = "UPDATE `tb_penyewaan` SET `status` = 'Selesai' WHERE `id_penyewaan` = '".$id."'";
		$hasil = mysqli_query($db, $query);
		$query1	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$id."'";
		$hasil1 = mysqli_query($db, $query1);
		while ($data1 = mysqli_fetch_array($hasil1)){
			$query1a	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data1['id_produk']."'";
			$hasil1a = mysqli_query($db, $query1a);
			$data1a = mysqli_fetch_array($hasil1a);
			$query = "UPDATE `tb_produk` SET `stok` = '".($data1a['stok'] + $data1['qty'])."' WHERE `id_produk` = '".$data1['id_produk']."'";
			$hasil = mysqli_query($db, $query);
		}
		if ($hasil){
			echo "<script language='JavaScript'>alert('Berhasil'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}if (!$hasil){
			echo "<script language='JavaScript'>alert('Gagal'); window.location = '".basename(__FILE__, '.php')."';</script>";
		}
	}
}
include "header.php";
$xpage	="dpenyewaan";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Data Penyewaan</h1><hr/>
		<?php
		if(isset($_GET['op'])){
			$op = $_GET['op'];
			if($op=="detail"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_penyewaan` WHERE `id_penyewaan` = '".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				$query2	= "SELECT * FROM `tb_login` WHERE `username` = '".$data['username']."'";
				$hasil2	= mysqli_query($db, $query2);
				$data2	= mysqli_fetch_array($hasil2);
				$st		= 0;
				echo "<center><h3>Detail Penyewaan #".$id."</h3><hr/>
				<table width=\"100%\">
					<tr>
						<td valign=\"top\">Customer</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['nama']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Alamat</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['alamat']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">No. Telp</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['notelp']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Tanggal Sewa</td><td valign=\"top\">:</td><td valign=\"top\"><b>".TanggalIndo($data['tglsewa'])."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Lama Sewa</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['lamasewa']." hari</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Tanggal Kembali</td><td valign=\"top\">:</td><td valign=\"top\"><b>".TanggalIndo($data['tglkembali'])."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Status</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['status']."</b></td>
					</tr>";
					if($data['status'] != "Belum Terbayar" AND $data['status'] != "Dibatalkan"){
						echo "<tr>
							<td valign=\"top\">Bank</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['bank']."</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Atas Nama Rekening</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['atasnama']."</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Jumlah Transfer</td><td valign=\"top\">:</td><td valign=\"top\"><b>".number_format($data['jumlahtransfer'],0,",",".").",-</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Bukti</td><td valign=\"top\">:</td><td valign=\"top\"><a href=\"img/bukti/".$data['id_penyewaan'].".jpg\" target=\"blank\"><img src=\"img/bukti/".$data['id_penyewaan'].".jpg\" width=\"150px\"></a></td>
						</tr>
						<tr>
							<td valign=\"top\">Keterangan</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['keterangan']."</b></td>
						</tr>";
						if($data['status'] == "Penyewaan" OR $data['status'] == "Selesai"){
							echo "<tr>
								<td valign=\"top\">Denda</td><td valign=\"top\">:</td><td valign=\"top\"><b>".number_format($data['denda'],0,",",".").",-</b></td>
							</tr>";
						}
					}
				echo "</table><br/>";
					$query3	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$id."'";
					$hasil3 = mysqli_query($db, $query3);
				echo "<table class=\"table table-striped\" width=\"100%\" style=\"border:1px solid #000;font-size:8pt;\">
					<thead style=\"background:#444444;color:#fff;\">
						<tr>
							<th style=\"border:1px solid #000;\">Produk</th>
							<th style=\"border:1px solid #000;\">Jumlah</th>
							<th style=\"border:1px solid #000;\">Sub Total</th>
						</tr>
					</thead>
					<tbody>";
					while ($data3 = mysqli_fetch_array($hasil3)){
						$query4	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data3['id_produk']."'";
						$hasil4	= mysqli_query($db, $query4);
						$data4	= mysqli_fetch_array($hasil4);
						$st		= $st + ($data3['harga'] * $data3['qty']);
						echo "<tr>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data4['nama']."</b><br/>Harga : <b>@".number_format($data3['harga'],0,",",".").",- /hari</b></td>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data3['qty']."</b></td>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".number_format(($data3['harga'] * $data3['qty']),0,",",".").",-</b></td>
						</tr>";
					}
					echo "<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Sub Total</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".number_format($st,0,",",".").",-</b></td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Lama Sewa</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".$data['lamasewa']." hari</b></td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Total</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".number_format($data['total'],0,",",".").",-</b></td>
					</tr>
					</tbody>
				</table>
				<br/><hr style=\"border:2px solid #000;\"/><br/><br/>
				</center>";
			}if($op=="cancel1"){
				$id		= $_GET['id'];
				?>
				<h2>Batalkan Penyewaan</h2>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=cancel&id=<?php echo $id;?>">
					<label>Keterangan Pembatalan</label><br/>
					<input type="text" name="a" class="form-control" placeholder="Keterangan Pembatalan" maxlength="120" style="width:100%" required/><br/>
					<input type="submit" value="PROSES BATAL" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><hr/><br/><br/>
				<?php
			}if($op=="konfirmasibayar"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_penyewaan` WHERE `id_penyewaan` = '".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				$query2	= "SELECT * FROM `tb_login` WHERE `username` = '".$data['username']."'";
				$hasil2	= mysqli_query($db, $query2);
				$data2	= mysqli_fetch_array($hasil2);
				$st		= 0;
				echo "<center><h3>Detail Penyewaan #".$id."</h3><hr/>
				<table width=\"100%\">
					<tr>
						<td valign=\"top\">Customer</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['nama']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Alamat</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['alamat']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">No. Telp</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['notelp']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Tanggal Sewa</td><td valign=\"top\">:</td><td valign=\"top\"><b>".TanggalIndo($data['tglsewa'])."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Lama Sewa</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['lamasewa']." hari</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Tanggal Kembali</td><td valign=\"top\">:</td><td valign=\"top\"><b>".TanggalIndo($data['tglkembali'])."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Status</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['status']."</b></td>
					</tr>";
					if($data['status'] != "Belum Terbayar" AND $data['status'] != "Dibatalkan"){
						echo "<tr>
							<td valign=\"top\">Bank</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['bank']."</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Atas Nama Rekening</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['atasnama']."</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Jumlah Transfer</td><td valign=\"top\">:</td><td valign=\"top\"><b>".number_format($data['jumlahtransfer'],0,",",".").",-</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Bukti</td><td valign=\"top\">:</td><td valign=\"top\"><a href=\"img/bukti/".$data['id_penyewaan'].".jpg\" target=\"blank\"><img src=\"img/bukti/".$data['id_penyewaan'].".jpg\" width=\"150px\"></a></td>
						</tr>
						<tr>
							<td valign=\"top\">Keterangan</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['keterangan']."</b></td>
						</tr>";
					}
				echo "</table><br/>";
					$query3	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$id."'";
					$hasil3 = mysqli_query($db, $query3);
				echo "<table class=\"table table-striped\" width=\"100%\" style=\"border:1px solid #000;font-size:8pt;\">
					<thead style=\"background:#444444;color:#fff;\">
						<tr>
							<th style=\"border:1px solid #000;\">Produk</th>
							<th style=\"border:1px solid #000;\">Jumlah</th>
							<th style=\"border:1px solid #000;\">Sub Total</th>
						</tr>
					</thead>
					<tbody>";
					while ($data3 = mysqli_fetch_array($hasil3)){
						$query4	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data3['id_produk']."'";
						$hasil4	= mysqli_query($db, $query4);
						$data4	= mysqli_fetch_array($hasil4);
						$st		= $st + ($data3['harga'] * $data3['qty']);
						echo "<tr>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data4['nama']."</b><br/>Harga : <b>@".number_format($data3['harga'],0,",",".").",- /hari</b></td>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data3['qty']."</b></td>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".number_format(($data3['harga'] * $data3['qty']),0,",",".").",-</b></td>
						</tr>";
					}
					echo "<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Sub Total</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".number_format($st,0,",",".").",-</b></td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Lama Sewa</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".$data['lamasewa']." hari</b></td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Total</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".number_format($data['total'],0,",",".").",-</b></td>
					</tr>
					</tbody>
				</table>
				<br/>
				</center>";
				?>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=konfirmbayar" enctype="multipart/form-data">
					<input type="hidden" name="a" value="<?php echo $data['id_penyewaan']; ?>"/>
					<input type="hidden" name="e" value="<?php echo $data['total']; ?>"/>
					<label>Bank Tujuan Transfer</label><br/>
					<input type="text" name="b" class="form-control" placeholder="Bank Tujuan Transfer" maxlength="72" style="width:100%" required/><br/>
					<label>Atas Nama Rekening</label><br/>
					<input type="text" name="c" class="form-control" placeholder="Atas Nama Rekening" maxlength="72" style="width:100%" required/><br/>
					<label>Jumlah Transfer</label><br/>
					<input type="text" name="d" class="form-control" placeholder="Jumlah Transfer" maxlength="72" style="width:100%" onkeypress="return isNumberKey(event)" required/><br/>
					<label>Bukti Transfer</label><br/>
					<input type="file" accept="image/JPG" name="img"> <font color="RED">(800 x 800px)</font><br/><br/>
					<input type="submit" value="KONFIRMASI BAYAR" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><br/><br/><hr style="border:2px solid #000;"/><br/><br/>
				<?php
			}if($op=="konfirmbayar2x"){
				$id		= $_GET['id'];
				$query	= "SELECT * FROM `tb_penyewaan` WHERE `id_penyewaan` = '".$id."'";
				$hasil	= mysqli_query($db, $query);
				$data	= mysqli_fetch_array($hasil);
				$query2	= "SELECT * FROM `tb_login` WHERE `username` = '".$data['username']."'";
				$hasil2	= mysqli_query($db, $query2);
				$data2	= mysqli_fetch_array($hasil2);
				$st		= 0;
				echo "<center><h3>Detail Penyewaan #".$id."</h3><hr/>
				<table width=\"100%\">
					<tr>
						<td valign=\"top\">Customer</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['nama']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Alamat</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['alamat']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">No. Telp</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data2['notelp']."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Tanggal Sewa</td><td valign=\"top\">:</td><td valign=\"top\"><b>".TanggalIndo($data['tglsewa'])."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Lama Sewa</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['lamasewa']." hari</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Tanggal Kembali</td><td valign=\"top\">:</td><td valign=\"top\"><b>".TanggalIndo($data['tglkembali'])."</b></td>
					</tr>
					<tr>
						<td valign=\"top\">Status</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['status']."</b></td>
					</tr>";
					if($data['status'] != "Belum Terbayar" AND $data['status'] != "Dibatalkan"){
						echo "<tr>
							<td valign=\"top\">Bank</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['bank']."</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Atas Nama Rekening</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['atasnama']."</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Jumlah Transfer</td><td valign=\"top\">:</td><td valign=\"top\"><b>".number_format($data['jumlahtransfer'],0,",",".").",-</b></td>
						</tr>
						<tr>
							<td valign=\"top\">Bukti</td><td valign=\"top\">:</td><td valign=\"top\"><a href=\"img/bukti/".$data['id_penyewaan'].".jpg\" target=\"blank\"><img src=\"img/bukti/".$data['id_penyewaan'].".jpg\" width=\"150px\"></a></td>
						</tr>
						<tr>
							<td valign=\"top\">Keterangan</td><td valign=\"top\">:</td><td valign=\"top\"><b>".$data['keterangan']."</b></td>
						</tr>";
					}
				echo "</table><br/>";
					$query3	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$id."'";
					$hasil3 = mysqli_query($db, $query3);
				echo "<table class=\"table table-striped\" width=\"100%\" style=\"border:1px solid #000;font-size:8pt;\">
					<thead style=\"background:#444444;color:#fff;\">
						<tr>
							<th style=\"border:1px solid #000;\">Produk</th>
							<th style=\"border:1px solid #000;\">Jumlah</th>
							<th style=\"border:1px solid #000;\">Sub Total</th>
						</tr>
					</thead>
					<tbody>";
					while ($data3 = mysqli_fetch_array($hasil3)){
						$query4	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$data3['id_produk']."'";
						$hasil4	= mysqli_query($db, $query4);
						$data4	= mysqli_fetch_array($hasil4);
						$st		= $st + ($data3['harga'] * $data3['qty']);
						echo "<tr>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data4['nama']."</b><br/>Harga : <b>@".number_format($data3['harga'],0,",",".").",- /hari</b></td>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data3['qty']."</b></td>
							<td valign=\"top\" style=\"border:1px solid #000;\"><b>".number_format(($data3['harga'] * $data3['qty']),0,",",".").",-</b></td>
						</tr>";
					}
					echo "<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Sub Total</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".number_format($st,0,",",".").",-</b></td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Lama Sewa</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".$data['lamasewa']." hari</b></td>
					</tr>
					<tr>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>Total</b></td>
						<td colspan=\"2\" valign=\"top\" style=\"background:#444444;color:#fff;border:1px solid #000;\"><b>".number_format($data['total'],0,",",".").",-</b></td>
					</tr>
					</tbody>
				</table>
				<br/>
				</center>";
				?>
				<form method="post" action="<?php echo basename(__FILE__, '.php');?>?op=konfirmbayar2">
					<input type="hidden" name="a" value="<?php echo $data['id_penyewaan']; ?>"/>
					<label>Keterangan</label><br/>
					<input type="text" name="b" class="form-control" placeholder="Keterangan" maxlength="120" style="width:100%" required/><br/><br/>
					<input type="submit" value="PROSES" class="btn btn-secondary" style="width:100%"/>
				</form>
				<br/><br/><br/><hr style="border:2px solid #000;"/><br/><br/>
				<?php
			}
		}
		if($_SESSION['level'] == "user"){
			$query = "SELECT * FROM `tb_penyewaan` WHERE `username` = '".$_SESSION['username']."'";
		}if($_SESSION['level'] != "user"){
			$query = "SELECT * FROM `tb_penyewaan`";
		}
		$hasil = mysqli_query($db, $query);
		if(mysqli_num_rows($hasil) < 1){
			echo "<center><h3>TIDAK ADA DATA</h3></center>";
		}if(mysqli_num_rows($hasil) >= 1){
			?>
			<table id="myTable" class="table table-striped" width="100%" style="border:1px solid #000;font-size:8pt;">
				<thead style="background:#444444;color:#fff;">
					<tr>
						<th style="border:1px solid #000;" width="10px">ID</th>
						<th style="border:1px solid #000;" width="100px">Waktu Order</th>
						<th style="border:1px solid #000;" width="100px">Sewa</th>
						<th style="border:1px solid #000;" width="100px">Biaya</th>
						<th style="border:1px solid #000;">Status</th>
						<th style="border:1px solid #000;" width="100px">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				while ($data = mysqli_fetch_array($hasil)){
					echo "<tr>
						<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data['id_penyewaan']."</b></td>
						<td valign=\"top\" style=\"border:1px solid #000;\">Order :<br/><b>".TanggalIndox($data['waktuorder'])."</b><br/>Jatuh Tempo Bayar :<br/><b>".TanggalIndox($data['waktutempo'])."</b></td>
						<td valign=\"top\" style=\"border:1px solid #000;\">Tanggal :<br/><b>".TanggalIndo($data['tglsewa'])."</b><br/>Lama Sewa :<br/><b>".$data['lamasewa']." hari</b><br/>Tanggal Kembali :<br/><b>".TanggalIndo($data['tglkembali'])."</b></td>
						<td valign=\"top\" style=\"border:1px solid #000;\">Sub Total (per hari) :<br/><b>".number_format($data['subtotal'],0,",",".").",-</b><br/>Total :<br/><b>".number_format($data['total'],0,",",".").",-</b>";
						if($data['status'] == "Penyewaan" OR $data['status'] == "Selesai"){
							echo "<br/>Denda :<br/><b>".number_format($data['denda'],0,",",".").",-</b>";
						}
						echo "</td>
						<td valign=\"top\" style=\"border:1px solid #000;\"><b>".$data['status']."</b></td>
						<td align=\"center\" valign=\"top\" style=\"border:1px solid #000;\">
							<b>
							<a href=\"".basename(__FILE__, '.php')."?op=detail&id=".$data['id_penyewaan']."\" style=\"color:#3892d6;\">Detail</a>";
							if($_SESSION['level'] == "user"){
								if($data['status'] == "Belum Terbayar"){
									echo "<hr/><a href=\"".basename(__FILE__, '.php')."?op=konfirmasibayar&id=".$data['id_penyewaan']."\" style=\"color:#3892d6;\">Konfirmasi Bayar</a>";
								}
							}if($_SESSION['level'] == "admin"){
								if($data['status'] == "Konfirmasi Bayar"){
									echo "<hr/><a href=\"".basename(__FILE__, '.php')."?op=konfirmbayar2x&id=".$data['id_penyewaan']."\" style=\"color:#3892d6;\">Diproses</a>
									<hr/><a href=\"".basename(__FILE__, '.php')."?op=cancel1&id=".$data['id_penyewaan']."\" style=\"color:#3892d6;\">Dibatalkan</a>";
								}if($data['status'] == "Penyewaan"){
									echo "<hr/><a href=\"".basename(__FILE__, '.php')."?op=kembali&id=".$data['id_penyewaan']."\" style=\"color:#3892d6;\">Dikembalikan</a>";
								}
							}
							echo "</b>
						</td>
					</tr>";
				}
				?>
				</tbody>
			</table>
		<?php
		}
		?>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>