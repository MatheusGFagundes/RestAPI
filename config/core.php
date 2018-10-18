<?php
	// mostra erros reportados
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	// url da home page
	$home_url="http://localhost/api/";

	// fornece o parametro da URL, pagina default é a 1 page given in URL parameter, default page is one
	$pagina = isset($_GET['page']) ? $_GET['page'] : 1;

	// condigura numero de itens por pagina
	$itens_por_pagina = 5;

	// calcula a clausula de limite para a query
	$para_item_numero = ($itens_por_pagina * $pagina) - $itens_por_pagina;
?>