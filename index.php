<?php
session_start();

if($_SESSION['autenticado'] == 'SI' ){


	include('pages/partials/head.php');
	include('pages/partials/nav.php');
	include('pages/inicio.php');
	?>
	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="myModal">
	  <div class="modal-dialog modal-sm">
	    
	  </div>
	</div>
	<?php
	include('pages/partials/footer.php');
	

}else{
	include('pages/partials/login.php');
}
?>

