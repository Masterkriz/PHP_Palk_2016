<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<title>Palgakalkulaator - PHP</title>
<link rel="stylesheet" href="kujundus.css">

</head>

<body>

<div id = "border">
<h1>Palgakalkulaator 2016</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<!-- MÄÄRAD kuu kohta aastal 2016
Brutopalga alammäär - 430 €
Tulumaksuvaba miinimum - 170 €
Töötaja töötuskindlustusmakse - 1.6 %
Tööandja töötuskindlustusmakse - 0.8 %
Kogumispension:

On liitunud kogumispensioniga - 2%
Kogumispension tõstmise avaldusega - 3%
Ei ole liitunund kogumispensioniga - 0%


 -->
 
<p>Sisesta lähtesumma (€):</p>
<input type="number" step="0.01" placeholder="0.00" required name="palk" title="Palun sisestage summa">

<input type="radio" name="bruto_neto" required value="bruto" title="Palun valige BRUTO või NETO">Brutopalk
<input type="radio" name="bruto_neto" value="neto">Netopalk<br>

<input type="checkbox" name="TU_min" value="suva" checked>Tulumaksuvaba miinimum (170 €/kuus)<br>
<input type="checkbox" name="Worker_insurance" value="suva" checked>Töötaja töötuskindlustusmakse (1.6%)<br>
<input type="checkbox" name="Employer_insurance" value="suva" checked>Tööandja töötuskindlustusmakse (0.8%)<br>

<p>Kogumispension:
<select name="pension">
  <option value="2">On liitunud kogumispensioniga - 2%</option>
  <option value="3">Kogumispension tõstmise avaldusega - 3%</option>
  <option value="0">Ei ole liitunud kogumispensioniga - 0%</option>
</select>

<br>
<br>

<input type="submit" name="form_submit" value="Arvuta">

<br>
<br>

<?php
session_start();

error_reporting(E_ALL);
     ini_set('display_errors', 1);

if (isset( $_POST['form_submit'] ) ) {
        // Do processing here.
		/*Palga arvutamise skript*/
		
		
		
// Brutopalga alammäär, tulumaks ning sotsiaalmaks
$bruto_alam = 430; // Brutopalga alammäär 2016 on 430 €
$neto_alam = 365.62; // Netopalga alammäär lähtudes 2016 aasta bruto alammäärast (430 €)

	
$tulumaks = 20; // protsenti
$tulumaks_jagatud = 0.2; // 20 %

$sotsiaalmaks = 33; // protsenti
$sotsiaalmaks_jagatud = 0.33; // 33 %

// POST - korja vormilt mõned andmed
$palk=$_POST['palk'];
$bruto_neto=$_POST['bruto_neto'];

$pension=$_POST['pension'];

// et endal kergem oleks aru saada (palgamuutuja nimi)
if($bruto_neto == 'bruto') {
	
	$bruto = round($palk, 2);
	
	if($bruto < $bruto_alam){
		
		exit("Sisestatud brutopalk ei saa olla väiksem kui " .$bruto_alam ."€ !");
		
	}
	
} else {
	
	$neto = round($palk, 2);
	
	if($neto < $neto_alam){
		
		exit("Sisestatud netopalk ei saa olla väiksem kui " .$neto_alam ."€ !");
		
	}
}

if($pension > 0) {
	
	$pension_jagatud = $pension / 100;
		
	} else {
		
		$pension_sum = 0;
		
	}

// Kontrollib kas Tulumaksuvaba miinimum on valitud
if(!empty($_POST["TU_min"])){
	
	$TU_min = 170; // Eurot - €
	
	
} else {
	
	$TU_min = 0; // Eurot - €
}

// Kontrollib kas Töötaja töötuskindlustusmakse on valitud
if(!empty($_POST["Worker_insurance"])){
	
	$worker_insurance = 1.6; //protsenti
	$worker_insurance_jagatud = 0.016; // 1.6 %
		
} else {
	
	$worker_insurance = 0; //prosenti
	$worker_insurance_jagatud = 0; //protsenti
}

// Kontrollib kas Tööandja töötuskindlustusmakse on valitud
if(!empty($_POST["Employer_insurance"])){
	
	$employer_insurance = 0.8; // protsenti
	$employer_insurance_jagatud = 0.008; // 0.8 %
	
} else {
	
	$employer_insurance = 0;
	$employer_insurance_jagatud = 0; // protsenti
}

// Kui valitud on "bruto" siis tee järgnev arvutus
if($bruto_neto == 'bruto') {
	
	// VALEMID
	$pension_sum = round($bruto * $pension_jagatud, 2); // brutopalk jagatud pensioniga
	$worker_insurance_sum = round($bruto * $worker_insurance_jagatud, 2);
	$tulumaks_sum = round(($bruto - $worker_insurance_sum - $pension_sum - $TU_min) * $tulumaks_jagatud, 2);
	$neto = round($bruto - $pension_sum - $worker_insurance_sum - $tulumaks_sum, 2);
	$employer_insurance_sum = round($palk * $employer_insurance_jagatud, 2);
	$sotsiaalmaks_sum = round($palk * $sotsiaalmaks_jagatud, 2);
	$employer_total = round($bruto + $employer_insurance_sum + $sotsiaalmaks_sum, 2);
	$katuseraha_maffiale = round($tulumaks_sum + $pension_sum + $worker_insurance_sum + $employer_insurance_sum + $sotsiaalmaks_sum, 2);
		
	// Töötaja arvutused
	echo "<strong>Töötaja palk ja maksud</strong>";
	echo "<br>";
	
	echo "<p>Brutopalk:<span class = 'sum'> " .number_format($bruto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Töötuskindlustus (töötaja)(". $worker_insurance. "%):<span class = 'sum'> " .number_format($worker_insurance_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Kogumispension (" .$pension. "%):<span class = 'sum'> " .number_format($pension_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Tulumaks (" .$tulumaks ."%):<span class = 'sum'> " .number_format($tulumaks_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Netosumma (kätte):<span class = 'sum'> " .number_format($neto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	
	
	// Tööandja arvutused
	
	echo "<strong>Tööandja kulud ja maksud</strong>";
	echo "<br>";
	echo "<p>Kogukulu tööandjale:<span class = 'sum'> " .number_format($employer_total, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Brutopalk:<span class = 'sum'> " .number_format($bruto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Sotsiaalmaks (" .$sotsiaalmaks . "%):<span class = 'sum'> " .number_format($sotsiaalmaks_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Töötuskindlustus (tööandja) (" .$employer_insurance ."%):<span class = 'sum'> " .number_format($employer_insurance_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	
	// Rahade jaotus arvutused
	
	echo "<strong>Rahade jaotus</strong>";
	echo "<br>";
	echo "<p>Kogukulu tööandjale:<span class = 'sum'> " .number_format($employer_total, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Töötajale (netosumma kätte):<span class = 'sum'> " .number_format($neto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Maksuametile (kõik maksud):<span class = 'sum'> " .number_format($katuseraha_maffiale, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	
	//PIRUKA LINK
	//echo "<a href='pirukas.php'>Ava pirukavaade</a>";
	echo "<img src='pirukas.php' alt='Siin on pirukas kui serveri PHP seaded lubavad seda kuvada...' />";
	
} else {
	
	// Kui valitud on "neto" siis tee järgnev arvutus
	
	//VALEMID
	$tulumaks_inverse = round((100 - $tulumaks) / 100, 2);
	
	$tulumaks_sum =  round(($neto - $TU_min)/$tulumaks_inverse - $neto + $TU_min, 2);
	$bruto = round(($neto + $tulumaks_sum)/(1-$pension_jagatud - $worker_insurance_jagatud), 2);
	$pension_sum = round($bruto * $pension_jagatud, 2); // brutopalk jagatud pensioniga
	$worker_insurance_sum = round($bruto * $worker_insurance_jagatud, 2);
	$employer_insurance_sum = round($bruto * $employer_insurance_jagatud, 2);
	$sotsiaalmaks_sum = round($bruto * $sotsiaalmaks_jagatud, 2);
	$employer_total = round($bruto + $employer_insurance_sum + $sotsiaalmaks_sum, 2);
	$katuseraha_maffiale = round($tulumaks_sum + $pension_sum + $worker_insurance_sum + $employer_insurance_sum + $sotsiaalmaks_sum, 2);
	
	// Töötaja arvutused
	echo "<strong>Töötaja palk ja maksud</strong>";
	echo "<br>";
	echo "<p>Brutopalk:<span class = 'sum'> " .number_format($bruto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Töötuskindlustus (töötaja)(". $worker_insurance. "%):<span class = 'sum'> " .number_format($worker_insurance_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Kogumispension (" .$pension. "%):<span class = 'sum'> " .number_format($pension_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Tulumaks (" .$tulumaks ."%):<span class = 'sum'> " .number_format($tulumaks_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Netosumma (kätte):<span class = 'sum'> " .number_format($neto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	
	// Tööandja arvutused
		
	echo "<strong>Tööandja kulud ja maksud</strong>";
	echo "<br>";
	echo "<p>Kogukulu tööandjale:<span class = 'sum'> " .number_format($employer_total, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Brutopalk:<span class = 'sum'> " .number_format($bruto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Sotsiaalmaks (" .$sotsiaalmaks . "%):<span class = 'sum'> " .number_format($sotsiaalmaks_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Töötuskindlustus (tööandja) (" .$employer_insurance ."%):<span class = 'sum'> " .number_format($employer_insurance_sum, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	
	// Rahade jaotus arvutused
	
	echo "<strong>Rahade jaotus</strong>";
	echo "<br>";
	echo "<p>Kogukulu tööandjale:<span class = 'sum'> " .number_format($employer_total, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Töötajale (netosumma kätte):<span class = 'sum'> " .number_format($neto, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	echo "<p>Maksuametile (kõik maksud):<span class = 'sum'> " .number_format($katuseraha_maffiale, 2, ",", " ") ." <span class = 'euro'>€</span></span></p>";
	
	//PIRUKA LINK
	//echo "<a href='pirukas.php'>Ava pirukavaade</a>";
	echo "<img src='pirukas.php' alt='Siin on pirukas kui serveri PHP seaded lubavad seda kuvada...' />";
	
	}

	// SESSION DATASET 1 - FROM
	$_SESSION['neto'] = $neto;
	$_SESSION['tulumaks'] = $tulumaks_sum;
	$_SESSION['sotsiaalmaks'] = $sotsiaalmaks_sum;
	$_SESSION['worker_insurance'] = $worker_insurance_sum;
	$_SESSION['pension'] = $pension_sum;

}// END

?>

</div>
</body>

</html>