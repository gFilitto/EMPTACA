<script type="text/javascript">
	
	$('#inicio').click(function(){
		$('li').removeClass('active');
		$('#myModal').modal('show');
		$.ajax({
			type:'GET', 
                url: 'pages/inicio.php',
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$('#inicio').addClass('active');
                 	$(".container").html(data);
                 
                  
                },error: function() {
					$("body").removeClass("loading");
					alert('Se ha producido un error');
				}
		});
	});

	$('.dropdown-toggle').dropdown();
	$('.detallado').click(function(){
		$('li').removeClass('active');
		$('#myModal').modal('show');
			var tomo = jQuery(this).attr("rel"); 
			var id_medi = $('.id_medi').val();
			//var id_admin = $('.id_admin').val();
			var id_trata = $('.id_trata').val();

			$.ajax(
            {                   
                type:'GET', 
                url: 'pages/detallado/formulario.php',
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$('#comision').addClass('active');
                 	$(".container").html(data);                 
                },error: function() {
					$("body").removeClass("loading");
					alert('Se ha producido un error');
				}
           });      
	});

	 $('.resumen').click(function(){
		$('li').removeClass('active');
		$('#myModal').modal('show');
			var tomo = jQuery(this).attr("rel"); 
			var id_medi = $('.id_medi').val();
			//var id_admin = $('.id_admin').val();
			var id_trata = $('.id_trata').val();

			$.ajax(
            {                   
                type:'GET', 
                url: 'pages/resumen/formulario.php',
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$('#comision').addClass('active');
                 	$(".container").html(data);
                 
                  
                },error: function() {
					$("body").removeClass("loading");
					alert('Se ha producido un error');
				}
            });
         });

	 

	 $('#export').click(function(){
		$('#myModal').modal('show');
		$.ajax({
			type:'GET', 
                url: 'pages/detallado/export.php',
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$('#inicio').addClass('active');
                 	$(".container").html(data);
                 
                  
                },error: function() {
					$("body").removeClass("loading");
					alert('Se ha producido un error');
				}
		});
	});

	$('.exportDB').click(function(){
		$('li').removeClass('active');
		$('#myModal').modal('show');
			var tomo = jQuery(this).attr("rel"); 
			var id_medi = $('.id_medi').val();
			//var id_admin = $('.id_admin').val();
			var id_trata = $('.id_trata').val();
			$.ajax(
            {                   
                type:'GET', 
                url: 'log/exportdb.php',
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$('.exportDB').addClass('active');
                 	$(".container").html(data);                 
                  
                },error: function() {
					$("body").removeClass("loading");
					alert('Se ha producido un error');
				}
            });
         });
</script>

	

</body>
</html>