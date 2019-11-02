<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
</div>
<footer class="py-5 bg-dark">
	<div class="container">
		<p class="m-0 text-center text-white">Copyright &copy;<?php echo $xthn." ".$xjudul1." by ".$xfounder;?></p>
	</div>
</footer>
<script src="jquery/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="datatables/jquery.dataTables.min.js"></script>
<script src="datepicker/jquery-ui.js"></script>
<script>
	$(document).ready(function(){
		$('#myTable').dataTable();
		modal.style.display = "block";
	});
	$(function() {
		$("#tanggal1").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tanggal2").datepicker({ dateFormat: 'dd-mm-yy' });
		$('#jam1').mask('99:99');
	});
	var modal = document.getElementById('myModal');
	var span = document.getElementsByClassName("close")[0];
	span.onclick = function() {
		modal.style.display = "none";
	}
	btn.onclick = function() {
		modal.style.display = "block";
	}
	window.onclick = function(event) {
		if (event.target == modal) {
		modal.style.display = "none";
		}
	}
	function isNumberKey(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
	}
	function RestrictSpace(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode == 32)
			return false;
			return true;
	}
	function konfirmasi(data){
		tanya = confirm('Anda yakin ingin hapus data?');
		if (tanya == true) return true;
		else return false;
	}
</script>
</body>
</html>