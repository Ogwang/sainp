<?php

/*include("Finance/lp_maker.php");
include("Finance/lp_solve.php");
require_once('Finance/Matrix.php');
require_once('Finance/Optimization.php');


$returns = array(array(1,2,3,4,5), array(2,4,5,7,8));
$weights = array(0.6, 0.4);

$opt = new Finance_Optimize($weights, $returns, 0.12);
$fun = $opt->optimizationFunction();
*/





?>

<?php

require_once("Finance/apiOctave.php");

$cov = array(array(1,2),array(2,3));
$ret = array(0.3,0.44);


$mat = new Finance_Matrix($ret,$cov);
$f = $mat->multiplicationMatrix($ret,$cov);


$teste = new Finance_apiOctave(1,$cov,$ret);
$tst = $teste->efficientFrontier();



?>