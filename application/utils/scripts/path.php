<?php
/**
 * O objetivo deste arquivo é determinar qual o sistema operacional do servidor web
 * e, com essa informação, configurar o caminho de busca do interpretador PHP.
 * @author Flávio Gomes da Silva Lisboa
 * @filesource 
 */
define('WINDOWS','WINDOWS');
define('LINUX','LINUX');

function configurePath($applicationName=null)
{
	if(is_null($applicationName))
	{
		$applicationName = getcwd(); 
	}
	
	/**
	 * Configura o caminho a ser procurado em todos os includes.
	 * Irá procurar no diretório ../library, no application/models
	 * e no caminho original do PHP.
	 */
	/**
	 * É interessante utilizar set_include_path para definir onde se encontram
	 * todos os arquivos do projeto, pois assim se evita que o mesmo código
	 * seja escrito várias vezes, gerando menos linhas e facilitando qualquer
	 * alteração de path.
	 */
	/**
	 * Seta include path para o funcionamento correto do framework ZEND e o modelo da aplicação (application/models)
	 ***OBRIGATÓRIO***
	 */
	#set_include_path('.' . PATH_SEPARATOR . './library' . PATH_SEPARATOR . './application/models/' . PATH_SEPARATOR.get_include_path());
	/*$operatingSystem = stripos($_SERVER['SERVER_SOFTWARE'],'win32')!== FALSE ? 'WINDOWS' : 'LINUX';
	$bar 			= ($operatingSystem == 'WINDOWS') ? '\\' : '/';
	$pathSeparator  = ($operatingSystem == 'WINDOWS') ? ';' : ':';
	$documentRoot   = ($operatingSystem == 'WINDOWS') ? str_replace('/','\\',$_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT'];*/
	/**
	 * Seta o path separando os paths por PATH_SEPARATOR ou :
	 * $path = ':'.$pathSeparator.$documentRoot.$bar.'teste'.$bar.'library';
	 * $path+= ':'.$pathSeparator.$documentRoot.$bar.'teste'.$bar.'application'.$bar.'models';
	 */
	/*$path = $pathSeparator.$documentRoot.$bar.'sig'.PATH_SEPARATOR.$pathSeparator.$documentRoot.$bar.'library'.PATH_SEPARATOR.'application'.$bar.'models';
	set_include_path(get_include_path().$path);*/
		
	/*$documentRoot = $_SERVER['DOCUMENT_ROOT'];
	// o teste abaixo irá variar dependendo das opções de sistema disponíveis
	//$operatingSystem = strpos('WIN32',strtoupper($_SERVER['SERVER_SOFTWARE'])) === FALSE ? LINUX : WINDOWS;
	//$operatingSystem = stripos($_SERVER['SERVER_SOFTWARE'], 'win32') !== FALSE ? 'WINDOWS' : 'LINUX';	
	$operatingSystem = strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') === FALSE ? LINUX : WINDOWS;

	// configuração padrão: sistema de arquivos do UNIX
	$bar 			= '/';
	$pathSeparator  = ':';
	
	if($operatingSystem == WINDOWS)
	{
		$bar = '\\';
		$pathSeparator = ';';
		$documentRoot = str_replace('/','\\',$documentRoot);
	}
	
	$path = $pathSeparator.$documentRoot.$bar.$applicationName.$bar.'library';
	$path.= $pathSeparator.$documentRoot.$bar.$applicationName.$bar.'application'.$bar.'models';
	$path.= $pathSeparator.$documentRoot.$bar.$applicationName.$bar.'application'.$bar.'utils';
	set_include_path(get_include_path().$path);*/
	
	
	/*echo  "get_include_path(),".get_include_path()."<br>";
	
	echo "<pre>";
	print_r(array(
			get_include_path(),
    		realpath(dirname(__FILE__) . '/../../../library')
		)
	);
	echo "</pre>";
	
	echo "<br>";*/
	
	set_include_path(implode(PATH_SEPARATOR,
		array(
			realpath(dirname($applicationName)),		
    		realpath(dirname(__FILE__).'/../../../library'),
    		realpath(dirname(__FILE__).'/../../models'),
    		get_include_path()
		)
	));
	
	#echo get_include_path();exit;
}
?>