<?php
$time_pre = microtime(true);
require_once __DIR__.'/vendor/autoload.php';

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

$packsFile =  file_get_contents('data/packs.json');
$packs = json_decode($packsFile);

$levelsFile =  file_get_contents('data/levels.json');
$levels = json_decode($levelsFile);

$worldsFile =  file_get_contents('data/worlds.json');
$worlds = json_decode($worldsFile);


$indexData = array();
$skillsData = array();

foreach($packs->packs as $pack){
	$indexData['packs'][] = array('name'=>$pack->name, 'id'=>$pack->id, 'type'=>$pack->type,'wave'=>$pack->wave);

	$packData = array();
	$packData['id'] = $pack->id;
	$packData['name'] = $pack->name;
	$packData['wave'] = $pack->wave;
	$packData['pieces'] = $pack->pieces;

	foreach($pack->pieces as $piece){
		foreach($piece->skills as $skill){
			$skillsData[$skill][] = $piece->name;
		}
	}

	$packTemplate = $twig->loadTemplate('pack.html');
	$packFile = 'docs/pack/'.$pack->id.'.html'; // or .php
	$fh = fopen($packFile, 'w'); // or die("error");
	$page = $packTemplate->render(array('pack' => $packData));
	fwrite($fh, $page);
}


foreach($levels->levels as $level){
	$indexData['levels'][] = array('name'=>$level->name,'id'=>$level->id, 'pack'=>$level->pack);

	$levelData = array();
	$levelData['name'] = $level->name;
	$levelData['pack'] = $level->pack;
	$levelData['minikits'] = $level->minikits;

	$levelTemplate = $twig->loadTemplate('level.html');
	$levelFile = 'docs/level/'.$level->id.'.html'; // or .php
	$fh = fopen($levelFile, 'w'); // or die("error");
	$page = $levelTemplate->render(array('level' => $levelData, 'skills' => $skillsData));
	fwrite($fh, $page);
}

foreach($worlds->worlds as $world){
	$worldData = array();
	$worldData['id'] = $world->id;
	$worldData['name'] = $world->name;
	$worldData['rulebreaker'] = $world->rulebreaker;
	$worldData['goldbricks'] = $world->goldbricks;
	$worldData['quests'] = $world->quests;
	$worldData['races'] = $world->races;
	$worldData['minikits'] = $world->minikits;
	$worldData['renovations'] = $world->renovations;

	$worldTemplate = $twig->loadTemplate('world.html');
	$worldFile = 'docs/world/'.$world->id.'.html'; // or .php
	$fh = fopen($worldFile, 'w'); // or die("error");
	$page = $worldTemplate->render(array('world' => $worldData));
	fwrite($fh, $page);
}

$indexTemplate = $twig->loadTemplate('index.html');
$fh = fopen('docs/index.html', 'w');
$page = $indexTemplate->render(array('data' => $indexData));
fwrite($fh, $page);
$time_post = microtime(true);
$time = $time_post-$time_pre;
echo("Build time: ".$time." seconds\n");