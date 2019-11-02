<?php
error_reporting(E_NOTICE | E_WARNING | E_DEPRECATED);
session_start();
set_time_limit(0);
include "config.php";
include "header.php";
$xpage	="kontak";
?>
<div class="row">
	<div class="col-lg-8">
		<h1 class="mt-4">Kontak</h1><hr/>
		<?php
		echo "<p align=\"justify\">
			<table>
				<tr>
					<td valign=\"top\" width=\"40px\">Alamat</td>
					<td valign=\"top\" width=\"40px\">:</td>
					<td valign=\"top\" width=\"40px\">".$xalamat."</td>
				</tr>
				<tr>
					<td valign=\"top\" width=\"40px\">No. Telp</td>
					<td valign=\"top\" width=\"40px\">:</td>
					<td valign=\"top\" width=\"40px\">".$xnotelp."</td>
				</tr>
				<tr>
					<td valign=\"top\" width=\"40px\">Whatsapp</td>
					<td valign=\"top\" width=\"40px\">:</td>
					<td valign=\"top\" width=\"40px\">".$xwa."</td>
				</tr>
				<tr>
					<td valign=\"top\" width=\"40px\">Email</td>
					<td valign=\"top\" width=\"40px\">:</td>
					<td valign=\"top\" width=\"40px\">".$xemail."</td>
				</tr>
			</table>
		</p>";
		?>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>