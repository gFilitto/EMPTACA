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

	 

	

	 function confirmar()
	{
		if(confirm('¿Estas seguro de visitar esta url?'))
			return true;
		else
			return false;
	}

	$('.exportDB').click(function(){
		$('li').removeClass('active');
		
			var tomo = jQuery(this).attr("rel"); 
			var id_medi = $('.id_medi').val();
			//var id_admin = $('.id_admin').val();
			var id_trata = $('.id_trata').val();
			var confi = confirmar();
			
			if(confi == true){
				$('#myModal').modal('show');
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
			}else{
				$('#myModal').modal('hide');
			}
			
         });
</script>

	

</body>
</html>