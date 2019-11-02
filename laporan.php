<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
if(isset($_GET['opx'])){
	$opx	= $_GET['opx'];
	if($opx == "cetakstokproduk"){
		?>
		<!DOCTYPE html>
		<html>
		<head>
		<style>
			@page {
				size: A4;
				margin: 0;
			}
			@media print {
				html, body {
					width: 210mm;
					height: 297mm;
				}
				page-break-after:always;
			}
		</style>
		</head>
		<body>
			<table width="100%" style="border-bottom:3px solid #000"><tr><td align="center"><img src="img/logo-mebel.png" width="150px"></td><td align="center"><h2 style="font-size:24px;margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;"><?php echo $xjudul1; ?></h2>
			<h2 style="font-size:22px;margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;">Data Laporan Stok Produk Tanggal <?php echo TanggalIndo($xtgl); ?></h2>
			Jetis Pajangan, Gondangsari, Juwiring, Klaten</td></tr></table><hr style="border-bottom:3px solid #000"/>
			<table cellpadding="0" cellspacing="0" style="border:2px solid #000;width:100%;">
				<thead style="background:#000;color:#fff;">
					<tr>
						<th align="center" valign="top" style="border-right:2px solid #000;">Kategori</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">ID Produk</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Nama</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Keterangan</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Harga</th>
						<th align="center" valign="top">Stok</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$aru	= array();
					$query	= "SELECT * FROM `tb_produk` ORDER BY `id_katproduk` ASC";
					$hasil	= mysqli_query($db, $query);
					while($data = mysqli_fetch_array($hasil)){
						$q = "SELECT * FROM `tb_katproduk` WHERE `id_katproduk` = '".$data['id_katproduk']."'";
						$h = mysqli_query($db, $q);
						$d = mysqli_fetch_array($h);
						if($data['stok'] <= 0){
							$clr = "color:RED;";
						}if($data['stok'] > 0){
							$clr = "";
						}
						echo "<tr>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">".$d['nama']."</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">".$data['id_produk']."</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">".$data['nama']."</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">".str_replace("\r\n", "<br/>", $data['keterangan'])."</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">Rp. ".number_format($data['harga'],0,",",".").",-</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;".$clr."\">".$data['stok']." pcs</td>
						</tr>";
					}
					?>
				</tbody>
			</table><br/><br/>
			<?php
			echo "<table align=\"center\" width=\"100%\">
				<tr><td></td><td align=\"center\" width=\"30%\">Solo, ".TanggalIndo($xtgl)."<br/><br/><br/><br/><br/><u> ".$_SESSION['nama']." </u><br/></td></tr>
			</table>";
		echo "<script language='JavaScript'>window.onload = window.print;</script>";
		?>
		</body>
		</html>
		<?php
	}if($opx == "cetakpenyewaan"){
		?>
		<!DOCTYPE html>
		<html>
		<head>
		<style>
			@page {
				size: A4;
				margin: 0;
			}
			@media print {
				html, body {
					width: 210mm;
					height: 297mm;
				}
				page-break-after:always;
			}
		</style>
		</head>
		<body>
			<?php
			$a	= $_POST['a'];
			$b	= $_POST['b'];
			$st	= 0;
			?>
			<table width="100%" style="border-bottom:3px solid #000"><tr><td align="center"><img src="img/logo.jpg" width="150px"></td><td align="center"><h2 style="font-size:24px;margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;"><?php echo $xjudul1; ?></h2>
			<h2 style="font-size:22px;margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;">Data Laporan Stok Produk Tanggal <?php echo TanggalIndo($xtgl); ?></h2>
			Karangasem, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57145</td></tr></table><hr style="border-bottom:3px solid #000"/>
			<table cellpadding="0" cellspacing="0" style="border:2px solid #000;width:100%;">
				<thead style="background:#000;color:#fff;">
					<tr>
						<th align="center" valign="top" style="border-right:2px solid #000;">Invoice</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Tanggal</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Customer</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Sewa</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Detail</th>
						<th align="center" valign="top" style="border-right:2px solid #000;">Total</th>
						<th align="center" valign="top">Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$aru	= array();
					$query	= "SELECT * FROM `tb_penyewaan` WHERE STR_TO_DATE(`waktuorder`, '%H:%i:%s %d-%m-%Y') BETWEEN STR_TO_DATE('00:00:00 ".$a."', '%H:%i:%s %d-%m-%Y') AND STR_TO_DATE('00:00:00 ".$b."', '%H:%i:%s %d-%m-%Y')";
					$hasil	= mysqli_query($db, $query);
					while($data = mysqli_fetch_array($hasil)){
						$q = "SELECT * FROM `tb_login` WHERE `username` = '".$data['username']."'";
						$h = mysqli_query($db, $q);
						$d = mysqli_fetch_array($h);
						echo "<tr>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\"><b>".$data['id_penyewaan']."</b></td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">Order :<br/><b>".TanggalIndox($data['waktuorder'])."</b><br/>Jatuh Tempo Bayar :<br/><b>".TanggalIndox($data['waktutempo'])."</b></td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">Username : ".$d['username']."<br/>Nama : ".$d['nama']."<br/>Alamat : ".$d['alamat']."<br/>No. Telp : ".$d['notelp']."</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">Tanggal :<br/><b>".TanggalIndo($data['tglsewa'])."</b><br/>Lama Sewa :<br/><b>".$data['lamasewa']." hari</b><br/>Tanggal Kembali :<br/><b>".TanggalIndo($data['tglkembali'])."</b></td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">
								<table cellpadding=\"0\" cellspacing=\"0\">
								<tr>
									<td valign=\"top\" style=\"border:1px solid #000;padding:5px;\">Produk</td>
									<td valign=\"top\" style=\"border:1px solid #000;padding:5px;\">Jumlah</td>
									<td valign=\"top\" style=\"border:1px solid #000;padding:5px;\">Sub Total</td>
								</tr>";
								$q3	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$data['id_penyewaan']."'";
								$h3 = mysqli_query($db, $q3);
								while ($d3 = mysqli_fetch_array($h3)){
									$query4	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$d3['id_produk']."'";
									$hasil4	= mysqli_query($db, $query4);
									$data4	= mysqli_fetch_array($hasil4);
									$st		= $st + ($d3['harga'] * $d3['qty']);
									echo "<tr>
										<td valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>".$data4['nama']."</b><br/>Harga : <b>@".number_format($d3['harga'],0,",",".").",- /hari</b></td>
										<td valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>".$d3['qty']."</b></td>
										<td valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>".number_format(($d3['harga'] * $d3['qty']),0,",",".").",-</b></td>
									</tr>";
								}
									echo "<tr>
										<td colspan=\"2\" valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>Sub Total</b></td>
										<td colspan=\"2\" valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>".number_format($st,0,",",".").",-</b></td>
									</tr>
									<tr>
										<td colspan=\"2\" valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>Lama Sewa</b></td>
										<td colspan=\"2\" valign=\"top\"  style=\"border:1px solid #000;padding:5px;\"><b>".$data['lamasewa']." hari</b></td>
									</tr>
									<tr>
										<td colspan=\"2\" valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>Total</b></td>
										<td colspan=\"2\" valign=\"top\" style=\"border:1px solid #000;padding:5px;\"><b>".number_format($data['total'],0,",",".").",-</b></td>
									</tr>
								</table>
							</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\">Sub Total (per hari) :<br/><b>".number_format($data['subtotal'],0,",",".").",-</b><br/>Total :<br/><b>".number_format($data['total'],0,",",".").",-</b>";
							if($data['status'] == "Penyewaan" OR $data['status'] == "Selesai"){
								echo "<br/>Denda :<br/><b>".number_format($data['denda'],0,",",".").",-</b>";
							}
							echo "</td>
							<td align=\"center\" valign=\"top\" style=\"border-right:2px solid #000;border-bottom:2px solid #000;\"><b>".$data['status']."</b></td>
						</tr>";
					}
					?>
				</tbody>
			</table>
			<?php
			echo "<table align=\"center\" width=\"100%\">
				<tr><td></td><td align=\"center\" width=\"30%\">Solo, ".TanggalIndo($xtgl)."<br/><br/><br/><br/><br/><u> ".$_SESSION['nama']." </u><br/></td></tr>
			</table>";
		echo "<script language='JavaScript'>window.onload = window.print;</script>";
		?>
		</body>
		</html>
		<?php
	}
}if(!isset($_GET['opx'])){
	include "header.php";
	$xpage	="laporan";
	?>
	<div class="row">
		<div class="col-lg-8">
			<h1 class="mt-4">Laporan Penyewaan</h1><hr/>
			<?php
			if(isset($_GET['op'])){
				$op = $_GET['op'];
				if($op=="penyewaan"){
					?>
					<h2>Pilih Periode Penyewaan</h2>
					<form method="post" action="<?php echo basename(__FILE__, '.php');?>?opx=cetakpenyewaan" target="_blank">
						<label>Dari Tanggal</label><br/>
						<input type="text" name="a" class="form-control" placeholder="DD-MM-YYYY" id="tanggal1" maxlength="10" style="width:100%" required/><br/>
						<label>Sampai Tanggal</label><br/>
						<input type="text" name="b" class="form-control" placeholder="DD-MM-YYYY" id="tanggal2" maxlength="10" style="width:100%" required/><br/><br/>
						<input type="submit" value="LIHAT LAPORAN" class="btn btn-secondary" style="width:100%"/>
					</form>
					<br/><hr/><br/><br/>
					<?php
				}
			}
			?>
		</div>
		<?php include "sidemenu.php"; ?>
	</div>
	<?php
	include "footer.php";
}
?>