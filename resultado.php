<?php
session_start();
$dataPoints = array( 
  array("y" =>  $_SESSION['potenciatransmnum'], "label" => "Potência de transmissão (dBm)" ), 
  array("y" => $_SESSION['ganhominnum'], "label" => " Ganho mínimo (dBi)" ),
  array("y" =>  $_SESSION['lbfnum'], "label" => "Perda espaço livre (dB)" ),
  array("y" => $_SESSION['eirpnum'], "label" => "e.i.r.p (dBm)" ),
  array("y" =>  $_SESSION['prnum'], "label" => "Potência Recebida (dBm)" )
);
?>
<html lang="pt-br">
<head>
  <title>E.C.E</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Potências"
	},
	axisY: {
		title: "dB"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## dB",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<style type="text/css">

body {
  background-color: #2C3E50;
}

form {
  border-top: solid #4682B4;

}
  .btn{
    margin-top: 1%;
  }

  .form-group{
    margin: 1% 0 1% 0;
  }

  #formDiv{
    margin-top: 1%;
    margin-bottom: 1%;
    padding: 2%;
    border: #e5e5e5;
    background: #fdfdfd;

  }
 
</style>

<body>

<div class="container" id="formDiv">
Cálculos de atenuação de espaço livre
<form role="form" class="form-horizontal" action="index.html" method="post"> 
<table class="table" >
  <thead class="thead-dark">
    <tr>
      <th scope="col" class="col-sm-4">Nome</th>
      <th scope="col" class="col-sm-2">Variável</th>
      <th scope="col" class="col-sm-3">Valor</th>
      <th scope="col" class="col-sm-2">Unidade</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Potencial Elétrico</td>
      <td>U</td>
      <td><?php echo $_SESSION['U']; ?> </td>
      <td>V</td>
    </tr>
    <tr>
      <td>Potência Recebida</td>
      <td>Pr</td>
      <td><?php echo $_SESSION['prw']; ?> </td>
      <td>W</td>
    </tr>
  </tbody>
</table>
<legend>Links ponto-a-área</legend>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col" class="col-sm-4">Nome</th>
      <th scope="col" class="col-sm-2">Variável</th>
      <th scope="col" class="col-sm-3">Valor</th>
      <th scope="col" class="col-sm-2">Unidade</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Potência equivalente radiada de forma isotrópica</td>
      <td>E.I.R.P</td>
      <td><?php echo $_SESSION['eirpw']; ?> </td>
      <td><?php echo $_SESSION['eirpwunid']; ?></td>
    </tr>
    <tr>
      <td>Campo Elétrico</td>
      <td>E</td>
      <td><?php echo $_SESSION['e']; ?> </td>
      <td>V/m</td>
    </tr>
    <tr>
      <td>Campo Magnético</td>
      <td>H</td>
      <td><?php echo $_SESSION['H']; ?> </td>
      <td>A/m</td>
    </tr>
  </tbody>
</table>
<legend>Links ponto-a-ponto</legend>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col" class="col-sm-4">Nome</th>
      <th scope="col" class="col-sm-2">Variável</th>
      <th scope="col" class="col-sm-3">Valor</th>
      <th scope="col" class="col-sm-2">Unidade</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Comprimento de onda</td>
      <td>&#955</td>
      <td><?php echo $_SESSION['lambda']; ?> </td>
      <td><?php echo $_SESSION['lambdaunid']; ?> </td>
    </tr>
    <tr>
      <td>Perda de transmissão básica de espaço livre</td>
      <td>Lbf</td>
      <td><?php echo $_SESSION['lbf']; ?> </td>
      <td>dB</td>
    </tr>
  </tbody>
</table>
<legend>Relações entre as características de uma onda plana</legend>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col" class="col-sm-4">Nome</th>
      <th scope="col" class="col-sm-2">Variável</th>
      <th scope="col" class="col-sm-3">Valor</th>
      <th scope="col" class="col-sm-2">Unidade</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Densidade de Potência</td>
      <td>S</td>
      <td><?php echo $_SESSION['s']; ?> </td>
      <td>W/m<sup>2</sup></td>
    </tr>
  </tbody>
</table>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<legend></legend>
<legend>Conclusão</legend>
<div><h5><?php echo $_SESSION['msg']; ?></h5></div>
<div class="modal-footer">
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" name="submit" id="botao_voltar">Voltar</button>
            </div>
        </div>
      </div>
</form>
</div>

</body>
</html>