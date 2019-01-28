<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="{{asset('css/bmd.css')}}">
</head>
<body style="background-color:white">
	<header>
		<div class="row text-center">
			<div class="col-md-8">
				<img class="img-fluid" width="450" src="{{asset('images/header_reportes.PNG')}}">
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><br>
				<h5 class="text-center font-weight-bold">INSTITUTO TECNOLÓGICO DE CHALATENANGO, ASOCIACIÓN AGAPE DE EL SALVADOR</h5>
				<h6 class="text-center font-weight-bold">SUPERVISIÓN {{ strtoupper($proceso)." ".$anio }}</h6><br>
			</div>
		</div>
	</header>
</body>
</html>