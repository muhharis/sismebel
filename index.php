<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
include "header.php";
$xpage	="index";
?>
<img src="img/dw.png" width="100%" height="400px">
<div class="row">
	<div class="col-lg-8">
		<center><h2 class="mt-4"><hr/>Selamat Datang Di<br/><br/><?php echo $xjudul2; ?>
		<br/><br/>Oleh:<br/><?php echo $xfounder; ?><hr/></h2><center>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>