<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set("Asia/Jakarta");
$undb	= "root";
$passdb	= "";
$hostdb	= "localhost";
$namadb	= "lembah";
$db		= mysqli_connect($hostdb,$undb,$passdb);
mysqli_select_db($db, $namadb) or die ("Database Gagal");
mysqli_query($db, "SET GLOBAL time_zone = `Asia/Jakarta`");
/* --------------------------------------------------------------- */
define('_HOST_NAME', $hostdb);
define('_DATABASE_USER_NAME', $undb);
define('_DATABASE_PASSWORD', $passdb);
define('_DATABASE_NAME', $namadb);
$dbConnection = new mysqli(_HOST_NAME, _DATABASE_USER_NAME, _DATABASE_PASSWORD, _DATABASE_NAME);
if($dbConnection->connect_error){
	trigger_error('Connection Failed: '. $dbConnection->connect_error, E_USER_ERROR);
}
/* --------------------------------------------------------------- */
$xtgl		= date("d-m-Y");
$xthn		= date("Y");
$xjam		= date("H:i:s");
$xtgljam	= $xjam." ".$xtgl;
$tglsession	= date("dmY");
$tsessy		= date("HisdmY");
$tsessz		= date("mY");
$tsessx		= md5($tglsession);
$xjudul1	= "Mebel Vian Jaya";
$xjudul2	= "Mebel Vian Jaya";
$xfounder	= "Alex Adi";
$xalamat	= "Jetis Pajangan, Gondangsari, Juwiring, Klaten";
$xnotelp	= "081-809-240-239";
$xwa		= "081-809-240-239";
$xemail		= "mebelvianjaya@yahoo.com";
$xbank		= "Silahkan Transfer ke Salah Satu Rekening Berikut, BCA 12000324455 atau MANDIRI 874442320002 atau BRI 1000002938253 Semua Atas Nama SUDOMO";
$xtentang	= "Mebel Vian Jaya menyediakan berbagai macam mebel dengan harga yang murah dan harga terjangkau antara lain meja, kursi, pintu, jendela, almari, dll. Selamat Belanja!";
/* --------------------------------------------------------------- */
$qa = "SELECT * FROM `tb_penyewaan` WHERE STR_TO_DATE(`waktutempo`, '%H:%i:%s %d-%m-%Y') < STR_TO_DATE('".date('H:i:s d-m-Y')."', '%H:%i:%s %d-%m-%Y') AND `status`='Belum Terbayar'";
$ha = mysqli_query($db, $qa);
while($da = mysqli_fetch_array($ha)){
	$deko = $da['id_penyewaan'];
	$query = "UPDATE `tb_penyewaan` SET `status` = 'Dibatalkan' WHERE `id_penyewaan` = '".$deko."'";
	$hasil = mysqli_query($db, $query);
}
/* --------------------------------------------------------------- */
$qa = "SELECT * FROM `tb_penyewaan` WHERE STR_TO_DATE(`tglkembali`, '%d-%m-%Y') < STR_TO_DATE('".date('d-m-Y')."', '%d-%m-%Y') AND `status` = 'Penyewaan'";
$ha = mysqli_query($db, $qa);
while($da = mysqli_fetch_array($ha)){
	$denda	= 0;
	$deko	= $da['id_penyewaan'];
	$yd1	= time();
	$yd2	= strtotime($da['tglkembali']);
	$dd		= $yd1 - $yd2;
	$dd		= round($dd / (60 * 60 * 24));
	$qa1	= "SELECT * FROM `tb_detailpenyewaan` WHERE `id_penyewaan` = '".$deko."'";
	$ha1	= mysqli_query($db, $qa1);
	while($da1 = mysqli_fetch_array($ha1)){
		$query4	= "SELECT * FROM `tb_produk` WHERE `id_produk` = '".$da1['id_produk']."'";
		$hasil4	= mysqli_query($db, $query4);
		$da2	= mysqli_fetch_array($hasil4);
		$qty	= $da1['qty'];
		$den	= $da2['denda'];
		$dend	= (int)$den * (int)$qty;
		$dend	= (int)$dend * (int)$dd;
		$denda	= (int)$denda + (int)$dend;
		$hasil = mysqli_query($db, "UPDATE `tb_detailpenyewaan` SET `denda` = '".$dend."' WHERE `id_detailpenyewaan` = '".$da1['id_detailpenyewaan']."'");
	}
	$query = "UPDATE `tb_penyewaan` SET `denda` = '".$denda."' WHERE `id_penyewaan` = '".$deko."'";
	$hasil = mysqli_query($db, $query);
}
/* --------------------------------------------------------------- */
$arei1	= array();
$qa = "SELECT * FROM `tb_detailpenyewaan`";
$ha = mysqli_query($db, $qa);
while($da = mysqli_fetch_array($ha)){
	$depro = $da['id_produk'];
	$qtpro = $da['qty'];
	$qa1 = "SELECT * FROM `tb_penyewaan` WHERE `id_penyewaan` = '".$da['id_penyewaan']."'";
	$ha1 = mysqli_query($db, $qa1);
	$da1 = mysqli_fetch_array($ha1);
	if($da1['status'] == "Selesai" OR $da1['status'] == "Penyewaan"){
		if(array_key_exists($depro, $arei1)){
			$uo	= $arei1[$depro] + $qtpro;
			$arei1[$depro] = $uo;
		}if(!array_key_exists($depro, $arei1)){
			$arei1[$depro] = $qtpro;
		}
	}
}
arsort($arei1);
array_slice($arei1, 0, 5);
/* --------------------------------------------------------------- */
function acak1(){
    $ix = "";
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	for($i=0;$i<15;$i++)
	$ix.=substr($chars,rand(0,strlen($chars)),1);
    return $ix;
}
function acak2(){
    $ix = "";
	$chars = "0123456789";
	for($i=0;$i<3;$i++)
	$ix.=substr($chars,rand(0,strlen($chars)),1);
    return $ix;
}
function acak3(){
    $ix = "";
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	for($i=0;$i<5;$i++)
	$ix.=substr($chars,rand(0,strlen($chars)),1);
    return $ix;
}
function anti_injection($data){
	$filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
	return $filter;
}
function TanggalIndo($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	$tglr	= substr($date, 0, 2);
	$bulanr = substr($date, 3, 2);
	$tahunr	= substr($date, 6, 4);
	$resultx = $tglr . " " . $BulanIndo[(int)$bulanr-1] . " ". $tahunr;		
	return($resultx);
}
function TanggalIndox($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	$jm1	= substr($date, 0, 2);
	$jm2	= substr($date, 3, 2);
	$jm3	= substr($date, 6, 2);
	$tglr	= substr($date, 9, 2);
	$bulanr = substr($date, 12, 2);
	$tahunr	= substr($date, 15, 4);
	$resultx = $tglr." ".$BulanIndo[(int)$bulanr-1]." ".$tahunr." ".$jm1.":".$jm2.":".$jm3;		
	return($resultx);
}
function TanggalIndoxx($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	$tglr	= substr($date, 9, 2);
	$bulanr = substr($date, 12, 2);
	$tahunr	= substr($date, 15, 4);
	$resultx = $tglr." ".$BulanIndo[(int)$bulanr-1]." ".$tahunr;		
	return($resultx);
}
function UploadGambar($new_name,$file,$dir,$width,$height){
   $vdir_upload = $dir;
   $vfile_upload = $vdir_upload . $_FILES[''.$file.'']["name"];
   move_uploaded_file($_FILES[''.$file.'']["tmp_name"], $dir.$_FILES[''.$file.'']["name"]);
   $im_src = imagecreatefromjpeg($vfile_upload);
   $src_width = imageSX($im_src);
   $src_height = imageSY($im_src);
   $dst_width = $width;
   $dst_height = $height;
   $im = imagecreatetruecolor($dst_width,$dst_height);
   imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
   imagejpeg($im,$vdir_upload . $new_name,100);
   imagedestroy($im_src);
   imagedestroy($im);
   $remove_small = unlink($vfile_upload);
}
/* --------------------------------------------------------------- */
$filesession	= join("",file("session"));
$pecahsession	= explode("\n", $filesession);
$session1		= rtrim($pecahsession[0]);
$session2		= rtrim($pecahsession[1]);
$pecahsession2	= explode("#",$session1);
$session12		= $pecahsession2[1];
if($session12 != $tglsession){
	$session	= acak1();
	$myfile = fopen("session", "w") or die("Unable to open file!");
	$txt = "session#".$tglsession. "\r\n";
	fwrite($myfile, $txt);
	$txt = $session;
	fwrite($myfile, $txt);
	fclose($myfile);
}if($session12 == $tglsession){
	$session = $session2;
}
$sessionenkrip = md5($tglsession.$session);
/* --------------------------------------------------------------- */
?>