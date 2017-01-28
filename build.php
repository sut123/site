<?php

require_once __DIR__.'/vendor/autoload.php';

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$packsFile =  file_get_contents('data/packs.json');
$packs = json_decode($packsFile);

$piecesFile =  file_get_contents('data/pieces.json');
$pieces = json_decode($piecesFile);

$indexData = array();

foreach($packs->packs as $pack){
	$packTemplate = $twig->loadTemplate('pack.html');
	$packData = array();
	$packData['name'] = $pack->name;
	$indexData[] = array('name'=>$pack->name, 'id'=>$pack->id, 'type'=>$pack->type, 'wave'=>$pack->wave);
	$packFile = 'docs/pack/'.$pack->id.'.html'; // or .php
	$fh = fopen($packFile, 'w'); // or die("error");
	$page = $packTemplate->render(array('pack' => $packData));
	fwrite($fh, $page);
}

$indexTemplate = $twig->loadTemplate('index.html');
$fh = fopen('docs/index.html', 'w');
$page = $indexTemplate->render(array('packs' => $indexData));
fwrite($fh, $page);
