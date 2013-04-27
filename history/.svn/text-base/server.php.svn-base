<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('America/Sao_Paulo');

$operatingSystem = stripos($_SERVER['SERVER_SOFTWARE'],'win32')!== FALSE ? 'WINDOWS' : 'LINUX';
$bar = $operatingSystem == 'WINDOWS' ? '\\' : '/';
$pathSeparator = $operatingSystem == 'WINDOWS' ? ';' : ':';
$documentRoot =  $operatingSystem == 'WINDOWS' ? str_replace('/','\\',$_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT'];

//$path = $pathSeparator.$documentRoot.$bar.'tramitacao_documentos'.$bar.'library';
$path = $pathSeparator.$documentRoot.$bar.'tramitacao_documentos'.$pathSeparator.$documentRoot.$bar.'tramitacao_documentos'.$bar.'library'.$pathSeparator.$documentRoot.$bar.'tramitacao_documentos'.$bar.'application'.$bar.'models';
set_include_path(get_include_path().$path);
//echo(get_include_path());
	
//set_include_path('.' . PATH_SEPARATOR . './library'
//. PATH_SEPARATOR . './application/models/'
//. PATH_SEPARATOR . get_include_path());

//Adiciona o autoloader do Zend Framework
require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

//Instancia o servidor PHP
$server = new Zend_Amf_Server();

//Adiciona o diretório php para que as classes sejam encontradas
$server->addDirectory($documentRoot.$bar.'tramitacao_documentos'.$bar.'application'.$bar.'models');//.$bar.'application'.$bar.'models'); //$documentRoot.$bar.'tramitacao_documentos'.$bar.'application'.$bar.'models');
#$server->addDirectory($documentRoot.$bar.'tramitacao_documentos'.$bar.'library');
#$server->addDirectory($documentRoot.$bar.'tramitacao_documentos/application/models');//.$bar.'application'.$bar.'models'); //$documentRoot.$bar.'tramitacao_documentos'.$bar.'application'.$bar.'models');
//echo $documentRoot.$bar.'tramitacao_documentos'.'/'.'application'.'/'.'models'.'/'; exit;

$server->setClass('Documento');
$server->setClassMap('Documento','Documento');


require_once "Zend/Loader.php";


Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');
Zend_Loader::loadClass('Documento');

// load configuration
$config = new Zend_Config_Ini('../../application/config.ini', 'database');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);


// setup database
$db = Zend_Db::factory($config->db->adapter,
$config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);
$registry->set('db', $db);

/*$d = new Documento();
echo"<pre>";print_r($d->Say());echo"</pre>";exit;

$arrDocumentos = array();
foreach($d->fetchAll() as $objD)
	$arrDocumentos []= array($objD->dc_id, $objD->dc_data_elaboracao);
print_r($arrDocumentos);

echo $d->Say();
exit;

//renderiza a saída, devidamente formatada no protocolo AMF
*/
echo $server->handle();
?>