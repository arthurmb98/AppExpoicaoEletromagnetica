<?php
error_reporting(0);
ini_set("display_errors", 0 );
//Constantes
$pi = 3.1415926535897932;

// Faixa de Operaçao
$faixaopmin = $_POST['faixaopmin'];
$faixaopmax = $_POST['faixaopmax'];
$unid = $_POST['unidfaixaop'];
if($unid == 'Ghz'){ // Converte para hz
    $faixaopmin = 1000*$faixaopmin*1000000;
    $faixaopmax = 1000*$faixaopmax*1000000;
}
if($unid == 'Mhz'){ // Converte para hz
    $faixaopmin = 1000000*$faixaopmin;
    $faixaopmax = 1000000*$faixaopmax;
}
if($unid == 'Khz'){ // Converte para hz
    $faixaopmin = 1000*$faixaopmin;
    $faixaopmax = 1000*$faixaopmax;
}
$faixaopminmhz = $faixaopmin/1000000;
$faixaopmaxmhz = $faixaopmax/1000000;

// Potencia Transmitida
$potenciatransm = $_POST['potenciatransm'];
$unid = $_POST['unidpotenciatransm'];
if($unid == 'mW'){
    $potenciatransmw = $potenciatransm/1000;
    $potenciatransm = 10 * log10( 1000 * $potenciatransmw / 1);
}
if($unid == 'kW'){
    $potenciatransmw = $potenciatransm*1000;
    $potenciatransm = 10 * log10( 1000 * $potenciatransmw / 1);
}
if($unid == 'W'){ // Converte para dBm
    $potenciatransmw = $potenciatransm;
    $potenciatransm = 10 * log10( 1000 * $potenciatransmw / 1);
}
if($unid == 'dBw'){ // Converte para dBm
    $potenciatransmw = 10**($potenciatransm / 10);
    $potenciatransm = 10 * log10( 1000 * $potenciatransmw / 1);
}
if($unid == 'dBk'){ // Converte para dBm
    $potenciatransmw = 10**($potenciatransm / 10)*1000;
    $potenciatransm = 10 * log10( 1000 * $potenciatransmw / 1);
}
if($unid == 'dBm'){
    $potenciatransmw = 10**($potenciatransm / 10) / 1000;
}
// Ganho Minimo
$ganhomin = $_POST['ganhomin'];
// Perda
$perdacabos = $_POST['perdacabo'];
$vswr = $_POST['vswr'];
if($vswr <= 0){
    $vswr=1;
}
// Torre
$torre = $_POST['torre'];
$unid = $_POST['unidtorre'];
if($unid == 'K'){ // Converte para Metros
    $torre = 1000*$torre;
}
// Distancia
$distancia = $_POST['distancia'];
$unid = $_POST['uniddistancia'];
if($unid == 'K'){ // Converte para Metros
    $distancia = 1000*$distancia;
}
// Nivel
$nivel = $_POST['nivel'];
$unid = $_POST['unidnivel'];
if($unid == 'K'){ // Converte para Metros
    $nivel = 1000*$nivel;
}
// Altura
$altura = $_POST['altura'];
$unid = $_POST['unidaltura'];
if($unid == 'K'){ // Converte para Metros
    $altura = 1000*$altura;
}
// Cálculo de perda pelo vswr (ROHDE & SCHWARZ);
$azinho = 20*log10(($vswr+1)/($vswr-1));
$taxaperda = (1/(10**($azinho/20)))**2;
if($vswr <= 1){
    $perdatotal = $perdacabos*$torre;
}else{
    $perdatotal = $taxaperda*$potenciatransm;
}
// Cálculo de atenuação de espaço livre ITU-R
// Point-to-point links
$h = $altura - $nivel + $torre;
$d = sqrt($h**2 + $distancia**2); // Distancia entre o ponto e a antena
$lambda = (3*10**8) / $faixaopmax; // Comprimento de onda: Velocidade da luz / frequencia
$lbf = 20*log10(4*$pi*$d/$lambda); // free-space basic transmission loss (dB)
//$lbf = 32.4 + 20*log10($faixaopmaxmhz) + 20*log10($d/1000); // free-space basic transmission loss (dB)
// Point-to-area links
$eirp = $potenciatransm + $ganhomin - $perdatotal; // Potência equivalente isotropicamente radiada
$eirpw = 10**($eirp / 10) / 1000; // eirp em Watts
$pr = $potenciatransm + $ganhomin - $perdatotal - $lbf; // Potência de transmissao
$prw = 10**($pr / 10) / 1000; // eirp em Watts
$e = sqrt(30*$eirpw)/$d; // Intensidade do campo elétrico (V/m)
// Relations between the characteristics of a plane wave
$s = ($e**2)/(120*$pi); // power flux-density (W/m2)
// potencial elétrico
$U = $e*$d;
// campo magnético
$H = $e/377;

// Chamando a pagina de resultados
session_start();
$_SESSION['perdacabosnum'] = $taxaperda*$potenciatransm;
$_SESSION['perdacabos'] = MostraNotacao($taxaperda*$potenciatransm);
$_SESSION['potenciatransmnum'] = $potenciatransm;
$_SESSION['eirpnum'] = $eirp;
$_SESSION['lbfnum'] = $lbf;
$_SESSION['ganhominnum'] = $ganhomin;
$_SESSION['prnum'] = $pr;
$_SESSION['prw'] = MostraNotacao($prw);
$_SESSION['potenciatransm'] = MostraNotacao($potenciatransm);
$_SESSION['potenciatransmw'] = MostraNotacao($potenciatransmw);
$_SESSION['ganhomin'] = MostraNotacao($ganhomin);
$_SESSION['eirpw'] = MostraNotacao($eirpw);
$_SESSION['eirpwunid'] = "W";
if($eirpw >= 1000){
    $_SESSION['eirpw'] = MostraNotacao($eirpw/1000);
    $_SESSION['eirpwunid'] = "KW";
}
$_SESSION['e'] = MostraNotacao($e);
$_SESSION['H'] = MostraNotacao($H);
$_SESSION['lambda'] = MostraNotacao($lambda);
$_SESSION['lambdaunid'] = "m";
if($lambda >= 1000){
    $_SESSION['lambda'] = MostraNotacao($lambda/1000);
    $_SESSION['lambdaunid'] = "Km";
}
$_SESSION['lbf'] = MostraNotacao($lbf);
$_SESSION['s'] = MostraNotacao($s);
$_SESSION['U'] = MostraNotacao($U);
//

$limiteE = 137;
$limiteH = 0.36;
$limiteS = 50;
//
$limiteE2 = 61;
$limiteH2 = 0.16;
$limiteS2 = 10;

if($faixaopminmhz < 2000){
    $limiteE = 3*sqrt($faixaopminmhz);
    $limiteH = $limiteE/377;
    $limiteS = $limiteE*$limiteH;
    //
    $limiteE2 = 1.375*sqrt($faixaopminmhz);
    $limiteH2 = $limiteE2/377;
    $limiteS2 = $limiteE2*$limiteH2;
}
if($faixaopminmhz < 400){
    $limiteE = 61;
    $limiteH = $limiteE/377;
    $limiteS = $limiteE*$limiteH;
    //
    $limiteE2 = 28;
    $limiteH2 = $limiteE/377;
    $limiteS2 = $limiteE*$limiteH;
}
if($faixaopminmhz < 10){
    $limiteE = 87/sqrt($faixaopminmhz);
    $limiteH = $limiteE/377;
    $limiteS = $limiteE*$limiteH;
    //
    $limiteE2 = 610/$faixaopminmhz;
    $limiteH2 = $limiteE/377;
    $limiteS2 = $limiteE*$limiteH; 
}
if($faixaopminmhz < 3.6){
    $limiteE = 170;
    $limiteH = $limiteE/377;
    $limiteS = $limiteE*$limiteH;
}
if($faixaopminmhz < 1){
    $limiteE2 = 83;
    $limiteH2 = $limiteE/377;
    $limiteS2 = $limiteE*$limiteH;
}
$limiteE = MostraNotacao($limiteE);
$limiteH = MostraNotacao($limiteH);
$limiteS = MostraNotacao($limiteS);
//
$limiteE2 = MostraNotacao($limiteE2);
$limiteH2 = MostraNotacao($limiteH2);
$limiteS2 = MostraNotacao($limiteS2);

$eirpwunid = $_SESSION['eirpwunid'];
// Verificando isenção da avaliação de conformidade
$msg = "A estação transmissora de radiocomunicação está isenta da avaliação de conformidade de acordo com a ANATEL 700/2018 CAPÍTULO IV. Pois ";
if($faixaopminmhz > 2000 AND $potenciatransmw < 2){
    $msg .= "a radiofrequência de operação é superior aos 2 GHz estabelecidos pela norma. faixa = $faixaopminmhz Mhz > 2000 Mhz. ";
    $num = $_SESSION['potenciatransmw'];
    $msg .= "a potência do transmissor é inferior aos 2 W estabelecidos pela norma. pot = $num W < 2 W. ";
}
if($eirpw < 4){
    $num = $_SESSION['eirpw'];
    $msg .= "A  potência equivalente isotropicamente radiada é inferior aos 4 W estabelecidos pela norma. e.i.r.p = $num $eirpwunid < 4 W. ";
}
else{
    $num1 = $_SESSION['potenciatransmw'];
    $num2 = $_SESSION['eirpw'];
    $msg = "A estação transmissora de radiocomunicação não está isenta da avaliação de conformidade 
    de acordo com a ANATEL 700/2018.
    Pois a faixa de operação é menor que 2000 Mhz, a potência de transmissão é maior que 2 W 
    e a potência equivalente isotropicamente radiada é maior que 4 W previstos na norma.<br>
    f = $faixaopminmhz Mhz &#8804 2000 Mhz. pot = $num1 W > 2 W. e.i.r.p = $num2 $eirpwunid > 4 W.";
}
// Verificando limites do Ato N° 458 Ocupacional 
$msg2 = "A estação transmissora de radiocomunicação está fora dos limites 
para exposição ocupacional do Ato N° 458 de 2019.<br>Pois ";
if($e > $limiteE){
    $msg2 .= "o campo elétrico é superior aos $limiteE V/m previstos na resolução.<br>";
}
if($H > $limiteH){
    $msg2 .= "o campo magnético é superior aos $limiteH A/m previstos na resolução.<br>";
}
if($s > $limiteS){
    $msg2 .= "densidade de potência é superior aos $limiteS W/m&sup2 previstos na resolução.<br>"; 
}
else{
    $num1 = $_SESSION['e'];
    $num2 = $_SESSION['H'];
    $num3 = $_SESSION['s'];
    $msg2 = "A estação transmissora de radiocomunicação está dentro dos limites 
    para exposição ocupacional do Ato N° 458 de 2019.
    Pois o campo elétrico é inferior aos $limiteE V/m, o campo magnético é inferior aos $limiteH A/m 
    e a densidade de potência é inferior aos $limiteS W/m&sup2, previstos na resolução.<br>
    E = $num1 V/m &#8804 $limiteE V/m. H = $num2 A/m &#8804 $limiteH A/m.  S = $num3 W/m&sup2 &#8804 $limiteS W/m&sup2. ";
}
// Verificando limites do Ato N°458 Populaçao Geral
$msg3 = "A estação transmissora de radiocomunicação está dentro dos limites 
para exposição da população em geral do Ato N° 458 de 2019. Pois ";
if($e < $limiteE2 AND $H < $limiteH2 AND $s < $limiteS2){
    $num1 = $_SESSION['e'];
    $num2 = $_SESSION['H'];
    $num3 = $_SESSION['s'];
    $msg3 .= "o campo elétrico é inferior aos $limiteE2 V/m, ";
    $msg3 .= "o campo magnético é inferior aos $limiteH2 A/m ";
    $msg3 .= "e a densidade de potência é inferior aos $limiteS2 W/m&sup2 previstos na resolução. "; 
    $msg3 .= "<br>E = $num1 V/m &#8804 $limiteE2 V/m. H = $num2 A/m &#8804 $limiteH2 A/m.  S = $num3 W/m&sup2 &#8804 $limiteS2 W/m&sup2. ";
    $msg2 = $msg3;
}
else{
    $msg3 = '';
}
$msg = $msg2."<br><br>".$msg;

$r = 0.143*sqrt($eirpw);
if($faixaopminmhz < 2000){
    $r = 6.38*sqrt($eirpw/$faixaopmaxmhz);
}
if($faixaopminmhz < 400){
    $r = 0.319*sqrt($eirpw);
}
if($faixaopminmhz < 10){
    $r = 0.1*sqrt($eirpw*$faixaopmaxmhz);
}
$rstring = MostraNotacao($r);
if($r > 1){
    $msg = $msg."<br><br>A distância mínima a antenas de estações transmissoras
    para atendimento aos limites de exposição a população em geral é r = $rstring m. ";
}else{
    $msg = $msg."<br><br>A distância mínima a antenas de estações transmissoras
    para atendimento aos limites de exposição a população em geral é r = 1 m. ";
}


$_SESSION['msg'] = $msg;

// Alertas e rediorecionamento de página
if($faixaopmin < 9000 OR $faixaopmaxmhz > 3000000){
    echo"<script>
        alert('Valores de frequência apenas entre 9 Khz a 300 Ghz');
        location.href = document.referrer;
        </script>";	
}
if($h > 15000){
    echo"<script>
        alert('Altura máxima da Antena transmissora de 15Km');
        location.href = document.referrer;
        </script>";	
}else{
    echo"<script>
        location.href= 'resultado.php';
        </script>";	
}


// Funçoes
function MostraNotacao($num){
    if($num < 0.0001 AND $num > 0){
        $var = (string)$num;
        $explode = explode( "E" ,$var);
        $substring = substr($explode[0], 0, 6);
        $numstring = $substring."x10<sup>".$explode[1]."</sup>";
    }
    else{
        $numstring = (string) round($num, 6);
        if($num > 10000){
            $numstring = (string) round($num, 2);
        }
    }
    return $numstring;
}

?>