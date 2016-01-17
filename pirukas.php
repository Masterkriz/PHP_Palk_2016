<?php
session_start();

// SESSION DATASET 2 - TO
$neto = $_SESSION['neto']; // kuvab eelmiselt lehelt muutuja $neto
$tulumaks = $_SESSION['tulumaks']; // kuvab eelmiselt muutuja $tulumaks_sum
$sotsiaalmaks = $_SESSION['sotsiaalmaks']; // kuvab eelmiselt muutuja $sotsiaalmaks_sum
$worker_insurance = $_SESSION['worker_insurance']; // kuvab eelmiselt muutuja $worker_insurance_sum
$pension = $_SESSION['pension']; // kuvab eelmiselt lehelt muutuja $pension_sum


// PIRUKAS OMAS MAHLAS
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_pie.php');

$data = array($neto,$tulumaks,$sotsiaalmaks,$worker_insurance,$pension);
$legends = array(   
	'Töötaja saab palgana kätte',   
	'Tulumaksuna laekub',   
	'Sotsiaalmaksuna laekub', 
	'Töötuskindlustusena laekub',
	'Pensionina laekub'
  );   
$graph = new PieGraph(650,550);

$theme_class="DefaultTheme";

$graph->title->Set("Töötaja Bruto Jagunemine (€)");
$graph->SetBox(true);

 $graph->legend->Pos(0.02, 0.02); 
 
$p1 = new PiePlot($data);
$graph->Add($p1);

$p1->SetLegends($legends);  

$p1->ShowBorder();
$p1->SetColor('black');
$p1->SetSliceColors(array('#1E90FF','#2E8B57','#ADFF2F','#f32727','#cc6600'));
$graph->Stroke();
?>