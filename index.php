<?php

if (isset($_GET['dl']))
{
	require 'libs/simplehtmldom_1_5/simple_html_dom.php';


	define('DATA_URL', 'http://inposdom.gob.do/codigopostal/jos_codigopostallist.php?start=');


	$html = file_get_html(DATA_URL);

	$pag_text = $html->find('#ewpagerform', 0)->children(0)->children(0)->children(2)->children(0)->innertext;

	preg_match('#(\d+)[^\d]+(\d+)$#', $pag_text, $matches);
	list($null, $per_page, $total_rows) = $matches;


	header('Content-Type: text/json');
	header('Content-Disposition: attachment; filename="postal-codes-rep-dom.csv"');

	echo '"URBANIZACION, SECTOR O PARAJE","PROVINCIA","CODIGO POSTAL"' . "\n";

	for ($page_num = 1; $page_num <= $total_rows; $page_num+=$per_page) 
	{
		$html = file_get_html(DATA_URL . $page_num);

		foreach($html->find('#gmp_jos_codigopostal', 0)->children(0)->find('tr') as $tr_num => $tr) 
		{
			if ($tr_num <= 3) continue;

			$row = array();

			foreach($tr->find('td') as $td) 
			{
				$row[] = str_replace('&nbsp;', ' ', $td->children(0)->innertext);
			}

			echo '"' . implode('","', $row) . "\"\n";
		}
	}

	exit;
}
	
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Códigos postales de la República Dominicana</title>
        <meta name="viewport" content="width=device-width" />
    </head>
    <body>

    	<h1>Códigos postales de la República Dominicana</h1>

    	<p><a href="?dl=csv">Descargar</a> en CSV.</p>

        <p>
        	La data es extraida de <a href="http://inposdom.gob.do/servicios/codigo-postal.html" target="_blank">INPOSDOM</a>. <br>

        	Si por alguna razón deja de funcionar por favor <a href="http://joserobinson.com/contacto/" target="_blank">avisame aquí</a>.
        </p>

        <p><strong>Github:</strong> <a href="https://github.com/jrobinsonc/codigos-postales-rd" target="_blank">https://github.com/jrobinsonc/codigos-postales-rd</a></p>

		<br><br>
		<hr />
		Powered by <a href="http://joserobinson.com/" target="_blank">JoseRobinson.com</a>

        <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-9394180-5', 'herokuapp.com');
		  ga('send', 'pageview');

		</script>
    </body>
</html>
