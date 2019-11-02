<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
include "header.php";
$xpage	="produk";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Tentang Kami</h1><hr/>
		<?php
		echo "<img src=\"img/mabel.jpg\" width=\"100%\" height=\"500px\"><p align=\"justify\">
			".$xtentang."
		</p>";
		?>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>