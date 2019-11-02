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
		<h1 class="mt-4">Berita</h1><hr/>
		<?php
		if(!empty($_GET['article'])){
			$idn = $_GET['article'];
			$query3 = "SELECT * FROM `tb_berita` WHERE `id_berita` = '".$idn."'";
			$hasil3 = mysqli_query($db, $query3);
			$data3 = mysqli_fetch_array($hasil3);
				$id3 = $data3['id_berita'];
				$jud3 = $data3['judul'];
				$isi = $data3['isi'];
				echo "<table><tr><td align=\"center\"><h4><b>".$jud3."</b></h4><br/></td></tr>
				<tr><td align=\"center\"><img src=\"img/berita/".$data3['id_berita'].".jpg\"  title=\"".$jud3."\" style=\"max-width:500px;\" /><br><p align=\"justify\">(".$data3['waktu'].")<br/>".$isi."
				</p></td></tr>
				<tr><td><p><br/></p></td></tr></table>";
				
			echo "<br/><center><hr/></center><br/><h3>Berita Lainnya</h3>";
			
			echo "<table width=\"100%\"><tr>";
			$query3 = "SELECT * FROM `tb_berita` ORDER BY `id_berita` DESC limit 2";
			$hasil3 = mysqli_query($db, $query3);
			while ($data3 = mysqli_fetch_array($hasil3)){
				$id3 = $data3['id_berita'];
				$jud3 = $data3['judul'];
				$is = $data3['isi'];
				$isi = substr($is, 0, 400);
				echo "<td width=\"50%\" align=\"center\"><img src=\"img/berita/".$data3['id_berita'].".jpg\" title=\"".$jud3."\" style=\"max-height:150px;\" /><h5><a href=\"berita?article=".$id3."\"><b>".$jud3."</b></a></h5></td>";
			}
			echo "</tr></table>";
		}if(empty($_GET['article'])){
			$sqlCount = "SELECT COUNT(`id_berita`) FROM `tb_berita`";  
			$rsCount = mysqli_fetch_array(mysqli_query($db, $sqlCount));  
			$banyakData = $rsCount[0];  
			$page = isset($_GET['page']) ? $_GET['page'] : 1;  
			$limit = 7;
			$mulai_dari = $limit * ($page - 1);
			
			echo "<table width=\"100%\">";
			$query3 = "SELECT * FROM `tb_berita` ORDER BY `id_berita` DESC limit ".$mulai_dari.",".$limit;
			$hasil3 = mysqli_query($db, $query3);
			while ($data3 = mysqli_fetch_array($hasil3)){
				$id3 = $data3['id_berita'];
				$jud3 = $data3['judul'];
				$is = $data3['isi'];
				$isi = substr($is, 0, 400);
				echo "<tr><td colspan=\"2\" align=\"center\"><h2><a href=\"".basename(__FILE__, '.php')."?article=".$id3."\">".$jud3."</a></h2></td></tr>
				<tr><td valign=\"TOP\"><img src=\"img/berita/".$data3['id_berita'].".jpg\" title=\"".$jud3."\" style=\"max-width:265px;\" /></td><td valign=\"TOP\"><p align=\"justify\">(".$data3['waktu'].")<br/>".$isi."
				.. <span><a href=\"".basename(__FILE__, '.php')."?article=".$id3."\">read more</a></span>
				</p></td></tr>
				<tr><td colspan=\"2\"><p><br/></p></td></tr>";
			}
		
			echo "</table>";
			$banyakHalaman = ceil($banyakData / $limit);
			$next = $page + 1;
			$prev = $page - 1;
			if ($banyakHalaman > 1){
				if($page == 1){
					echo"<a href=\"".basename(__FILE__, '.php')."?page=".$next."\" style=\" float:right;\"> Selanjutnya &#187; </a>";
				}if($page == $banyakHalaman){
					echo"<a href=\"".basename(__FILE__, '.php')."?page=".$prev."\"> &#171; Sebelumnya </a>";
				}if($page != $banyakHalaman && $page != 1){
					echo"<a href=\"".basename(__FILE__, '.php')."?page=".$prev."\"> &#171; Sebelumnya </a> <a href=\"".basename(__FILE__, '.php')."?page=".$next."\" style=\" float:right;\"> Selanjutnya &#187; </a>";
				}
			}
		}
		?>
	</div>
	<?php include "sidemenu.php"; ?>
</div>
<?php
include "footer.php";
?>