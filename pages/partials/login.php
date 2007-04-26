<!DOCTYPE html>
<html>
<head>
	<title>EMPTACA / Inicio de Sesión</title>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="css/custom_style.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="css/theme.css">
	<script src="js/jquery.js"></script>

	<link rel="shortcut icon" href="img/tapa.ico">
</head>
<body style="background:#efefef;">
 <body>
     <div class="login-a">
	     <div class="container" >
	        <div class="row">
	            <div class="col-md-4 col-md-offset-4">
	                <div class="login-panel panel panel-default">
	                    <div class="panel-heading" style="border:1px solid #C9C9D0;">
	                        <h3 class="panel-title">Inicio de Sesión</h3>
	                    </div>
	                    <div class="panel-body border-body">
	                        <form role="form" id="formData">
	                            <fieldset>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="Usuario" name="user" id="user" type="text" autofocus>
	                                </div>
	                                <div class="form-group">
	                                    <input class="form-control" placeholder="Contraseña" name="password" type="password" value="" id="pass">
	                                </div>
	                              <!--  <span style="float:right;"><a href="">¿Olvidaste tu contraseña?</a></span><br>
	                                <div class="checkbox">
	                                    <label>
	                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
	                                    </label>
	                                </div> -->
	                                <!-- Change this to a button or input when using this as a form -->
	                                <a href="#" class="btn btn-lg btn-success btn-block" style="margin-top:5px;" id="Login">Iniciar</a>
	                            </fieldset>
	                        </form>
	                        <div id="respuesta">
	                           
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	  </div>
    </body>
</body>
</html>
<script type="text/javascript">
	$(document).ready(function(){
		$('#Login').click(function(){
			$('#myModal').modal('show');
			var user = $('#user').val();
			var pass = $('#pass').val();
			$.ajax(
            {                   
               	type:'GET', 
                url: 'log/sesion.php?user='+user+'&&pass='+pass,
                async:false,
                success: function(data) {                   
                 	$('#myModal').modal('hide');
                 	$("#respuesta").html(data);
                 	
                  
                },error: function() {
						$('#myModal').modal('hide');
					alert('Se ha producido un error');
				}
            });	
		});
	});

</script>