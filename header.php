<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php echo $xjudul1." | ".$xfounder;?></title>
	<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="datatables/jquery.dataTables.min.css"></style>
	<link href="datepicker/jquery-ui.css" rel="stylesheet" />
</head>
<body style="background:#F8F8FF;">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<div class="container">
		<a class="navbar-brand" href="index"><?php echo $xjudul1;?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item"><a class="nav-link" href="index">Home</a></li>
				<li class="nav-item"><a class="nav-link" href="produk">Produk</a></li>
				<!--<li class="nav-item"><a class="nav-link" href="berita">Berita</a></li> -->
				<li class="nav-item"><a class="nav-link" href="tentang">Tentang Kami</a></li>
				<li class="nav-item"><a class="nav-link" href="kontak">Kontak</a></li>
				<?php
				if(!isset($_SESSION[$session])){
					?>
					<li class="nav-item"><a class="nav-link" href="login">Login</a></li>
					<li class="nav-item"><a class="nav-link" href="buatakun">Buat Akun</a></li>
					<?php
				}if(isset($_SESSION[$session])){
					?>
					<li class="nav-item"><a class="nav-link" href="logout">Logout</a></li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
</nav>
<div class="container" style="background:#fff;">