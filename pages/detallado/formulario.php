
	<script>
  $(function() {
    $( "#datepicker" ).datepicker({
    	dateFormat: "dd-mm-yy",
    	dayNames: [ "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" ],
    	dayNamesShort: [ "Do", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
    	dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
    	 monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
    	 changeYear: true,
    	 changeMonth: true,
    	 monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ]
    });
     $( "#datepicker1" ).datepicker({
    	dateFormat: "dd-mm-yy",
    	dayNames: [ "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" ],
    	dayNamesShort: [ "Do", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab" ],
    	dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
    	 monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
    	 changeYear: true,
    	 changeMonth: true,
    	 monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ]
    });
   
   $("#aceptar").click(function(){
   		$('#myModal').modal('show');
   		var fecha = $('#datepicker').val();
   		var fecha1 = $('#datepicker1').val();
   		var cargo = $('#cargo').val();
      var ven = $('#vendor').val();
  		$.ajax(
            {                   
                type:'GET', 
                url: 'pages/detallado/listado.php?fecha='+fecha+'&&fecha1='+fecha1+'&&cargo='+cargo+'&&vendedores='+ven,
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$(".container").html(data);
                 	
                  
                },error: function() {
						$('#myModal').modal('hide');
					alert('Se ha producido un error');
				}
            });	
  		}); 

  	});
   
  </script>

<form>
	<fieldset>
	<div class="row">
		<div class="col-md-4 form">
			<div class="form-group">
				<label for="fecha">Fecha:</label>
				<input type="text" id="datepicker" class="form-control">
			</div>
		</div>
		<div class="col-md-4 form">
			<div class="form-group">
				<label for="fecha">Fecha:</label>
				<input type="text" id="datepicker1" class="form-control">
			</div>
    </div>
    <div class="col-md-4 form">
    <div class="form-group">
        <label for="fecha">Opciones:</label>
        <select name="car" class="form-control" id="cargo">
          <option value="">-Selccione-</option>
          <option value="RDV">RDV</option>
          <option value="SUPERVISOR">SUPERVISOR</option>
          <option vlaue="GTE VENTAS">GTE VENTAS</option>
        </select>
      </div>
    </div>
    <div class="col-md-4 form">
      <div class="form-group">
       <label for="fecha">Nombre Vendedor:</label>
        <select name="vendedores" class="form-control" id="vendor">
          <option value="">-Selccione-</option>
          
        </select>
      </div>
    </div>
		<div class="col-md-4 form">
				<button type="submit" class="btn btn-primary" id="aceptar">Aceptar</button>
			
		</div>
		
	</div>
	</fieldset>
</form>
<script type="text/javascript">
  $('#cargo').change(function(){
      $("#cargo option:selected").each(function () {
            car=$(this).val();
            $.post("pages/detallado/vendedores.php", { car: car }, function(data){
            $("#vendor").html(data);
            });

      });
  });
</script>
  