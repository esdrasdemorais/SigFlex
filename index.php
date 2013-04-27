<?php
/**
 * 
 * Sess�o de configura��o de paths e erros
 */

/** Configura as mensagens de erro que devem ser apresentadas para mostrar os erros apenas nos testes (precisa estar setado no PHP.ini) */
error_reporting(E_ALL|E_STRICT);

/** Seta o timezone pra s�o paulo (>=PHP 5.1) */
setlocale(LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');
/** Configura o formato para moeda */
setlocale(LC_MONETARY,'ptb');

// Step 1: APPLICATION CONSTANTS - Set the constants to use in this application.
// These constants are accessible throughout the application, even in ini 
// files. We optionally set APPLICATION_PATH here in case our entry point 
// isn't index.php (e.g., if required from our test suite or a script).
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));
defined('APPLICATION_ENVIRONMENT')
    || define('APPLICATION_ENVIRONMENT', 'databaseAccess');
defined('APPLICATION_ENVIRONMENT_USUARIOS')
	|| define('APPLICATION_ENVIRONMENT_USUARIOS', 'databaseUser');
   
// configura o caminho
require_once('application/utils/scripts/path.php');
configurePath(basename(getcwd()));

/*// carrega classe que far� a inicializa��o do Zend Framework
require('application/utils/classes/Bootstrap.php');
new Bootstrap($_SERVER['PHP_SELF']);*/

require_once 'library/Zend/Loader/Autoloader.php';
$autoLoader = Zend_Loader_Autoloader::getInstance();
spl_autoload_unregister(array($autoLoader, 'autoload'));

Zend_Loader_Autoloader::resetInstance();
$autoLoader = Zend_Loader_Autoloader::getInstance();
$autoLoader->setFallbackAutoloader(true);
$autoLoader->registerNamespace('PHPUnit_');

/**
 * Faz o include do componente Zend_Loader.
 * Este include � OBRIGAT�RIO.
 * Zend_Loader carrega arquivos, classes e recursos
 * dinamicamente em sua aplica��o PHP.
 * => suporta autocarregamento da SPL (Standard PHP Library)
 * => suporta include_path
 * => fornece mecanismo de falha baseado em exce��o
 */
require_once("library/Zend/Loader.php");

/**
 * O m�todo loadClass � respons�vel por incluir o arquivo respons�vel pela classe.
 * O acesso a n�veis dos diret�rios do framework ZEND � feito atrav�s do "_" n�o da "/".
 */

/**
 * 
 * Carregando classes necess�rias
 */
 
/**
* O registro (Zend_Registry) � um cont�iner para armazenar objetos e valores
* no espa�o da aplica��o. Armazenar um objeto ou valor
* no registro torna o mesmo sempre dispon�vel ao longo
* da aplica��o durante o tempo de vida da requisi��o.
* Este mecanismo � freq�entemente uma alternativa aceit�vel
* ao uso de vari�veis globais.
* => fornece armazenamento acess�vel globalmente para objetos
* e valores
* => fornece os padr�es iterator, array e indexed access
*/
Zend_Loader::loadClass('Zend_Registry');

Zend_Loader::loadClass('Zend_Controller_Front');  	/** Classe de controladores */
Zend_Loader::loadClass("Zend_View"); 				/* Classe das vis�es */
Zend_Loader::loadClass('Zend_Config_Ini'); 			/** Classe usada para configura��es */
Zend_Loader::loadClass('Zend_Db'); 					/** Classe para acesso a base de dados */
Zend_Loader::loadClass('Zend_Db_Table'); 			/** Classe para usar as tabelas como objetos */
Zend_Loader::loadClass('Zend_Filter_Input');		/** Classe usada para filtrar os dados */

Zend_Loader::loadClass('Zend_Session'); 			/** Inclui o suporte a sess�es. S� � necess�rio caso seja usado. */
Zend_Loader::loadClass('Zend_Session_Namespace'); 	/** Classe usada para armazenar e recuperar dados da sess�o */
Zend_Loader::loadClass("Zend_Controller_Plugin_Abstract");
//Zend_Loader::loadClass("SecurityPlugin");
Zend_Loader::loadClass("Zend_Auth");

Zend_Loader::loadClass('Zend_Form');
#Zend_Loader::loadClass('LoginForm');

/** O m�todo set � respons�vel por armazenar vari�veis que podem ser usadas
 * pelos aplicativos. Aqui, registrando os arrays post e get com dados vindos do usu�rio.
 * o Zend_Filter limpa os dados.
 */
Zend_Registry::set('post', new Zend_Filter_Input(NULL,NULL,$_POST));
Zend_Registry::set('get', new Zend_Filter_Input(NULL,NULL,$_GET));

/** Inicia a sess�o global */
Zend_Session::start();

/** Cria o manipulador da dess�o */
Zend_Registry::set('session', new Zend_Session_Namespace());

/** Parte das vis�es (Views) */
$objView = new Zend_View(); /** Cria um novo objeto do tipo view */
$objView->setEncoding('UTF-8'); /** Configura a codifica��o das p�ginas */
$objView->setEscape('htmlentities'); /** Escapar entradas HTML */
$objView->setBasePath(APPLICATION_PATH.'/application/views/');	/** Define o diret�rio onde estar�o as vis�es */
$objView->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper'); /** Adiciona a lib js Dojo ao objeto View */
Zend_Registry::set('view', $objView); 			/** Registra na mem�ria a vari�vel view que indica a vis�o */

/**
* Este m�todo tenta carregar o arquivo passado como par�metro procurando no path
* definido com a fun��o set_include_path. Caso ele n�o consiga encontrar o arquivo,
* � gerada uma exce��o que indica arquivo inexistente ou sem acesso. O m�todo
* considera os underscores no nome do arquivo como subdiret�rios. Por exemplo, o
* comando Zend::loadClass('Zend_Controller_Front') faz a importa��o do arquivo
* ../zendframework/library/Zend/Controller/Front.php.
* Seguir esse padr�o facilita o entendimento da estrutura do projeto.
* Essa classe se encontra em Zend/Controller/Front.php
* Pode ser "loadado" diretamente pelo nome se preferir
* Para come�ar n�s precisamos "loadar" primeiro o front controller
* Ele faz um controle autom�tico para detectar a base URL e fazer o redirecionamento correto
*/
$objControlador = Zend_Controller_Front::getInstance();

/** Configura o controlador do projeto.
 * O Controlador, por acaso, � o index.php
 */
$baseUrl = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/index.php'));

/** Configura o endere�o do controlador do projeto */
$objControlador->setbaseUrl($baseUrl);

/* Mostrar exce��es (apenas para testes) */
$objControlador->throwExceptions(TRUE);

// Step 3: CONTROLLER DIRECTORY SETUP - Point the front controller to your action
// controller directory.
#$objControlador->setControllerDirectory('./application/controllers'); // seta diret�rio com nossos controllers
$objControlador->setControllerDirectory(APPLICATION_PATH.'/controllers'); // seta diret�rio com nossos controllers

// Step 4: APPLICATION ENVIRONMENT - Set the current environment.
// Set a variable in the front controller indicating the current environment --
// commonly one of development, staging, testing, production, but wholly
// dependent on your organization's and/or site's needs.
#$objControlador->setParam('env', APPLICATION_ENVIRONMENT);
#$objControlador->registerPlugin(new SecurityPlugin());

/** Configura a conex�o com a base de dados, pegando as vari�veis do arquivo de configura��o. */
try
{
	/**
	* DATABASE ADAPTER - Setup the database adapter
	* Zend_Db implements a factory interface that allows developers to pass in an
	* adapter name and some parameters that will create an appropriate database
	* adapter object.  In this instance, we will be using the values found in the
	* "database" section of the configuration obj.
	*/
	Zend_Loader::loadClass('Zend_Debug');
	/*Zend_Debug::dump(PDO::getAvailableDrivers());*/
	
	/**************************************** MSACCESS *****************************************************/
	/**
	 * CONFIGURATION - Setup the configuration object
	 * The Zend_Config_Ini component will parse the ini file, and resolve all of
	 * the values for the given section.  Here we will be using the section name
	 * that corresponds to the APP's Environment.
	 * Configura��es da diretiva [database] referente a base de dados.
	 * Indica onde est�o as configura��es do projeto.
	 * Est�o no arquivo config.ini na se��o (diretiva) database.
	 *
	 */
	$objConfig = new Zend_Config_Ini('./application/config.ini', APPLICATION_ENVIRONMENT); //'database'
	#$objConfig = new Zend_Config_Ini(APPLICATION_PATH.'\config.ini', APPLICATION_ENVIRONMENT); //'database'
	
	/** Registra na mem�ria o objeto Zend_Config_Ini config */
	Zend_Registry::set('config', $objConfig);
	
	// Objeto de Conex�o com a base Acess sigOffice.mdb - Zend_Db::factory(driver, arrayParametrosConexao)
	$objDb = Zend_Db::factory($objConfig->db->adapter, $objConfig->db->config->toArray());
	/**************************************** MSACCESS *****************************************************/
	
	/******************************************** MS SQL Server ************************************************/
	// Diretiva com os par�metros de conex�o com a base dbUsuarios do SQL Server
	$objConfigUsers = new Zend_Config_Ini('./application/config.ini', APPLICATION_ENVIRONMENT_USUARIOS); 
	
	/** Registra na mem�ria o objeto Zend_Config_Ini config SqlServer */
	Zend_Registry::set('config', $objConfigUsers);
	
	// Config do SQL Server base dbUsuarios
	$objDbUsers = Zend_Db::factory($objConfigUsers->db->adapter, $objConfigUsers->db->config->toArray());
	
	// Objeto de Conex�o com a base Sql Server dbUsuarios
	
	/******************************************** MS SQL Server ************************************************/
}
catch(Zend_Db_Adapter_Exception $zdbaex)
{
    echo $zdbaex->getMessage();
}
catch(Zend_Exception $ze)
{
	echo $ze->getMessage();
}
catch(Exception $e)
{
	echo $e->getMessage();
}

/************************** DEBUG **************************/
$sql = 'SELECT * FROM funcionario';
/*echo "<br>".$sql;
$result = $objDb->query($sql);
echo "<!-- -----------------<pre>";
echo "count=".($result->count());
foreach($result as $row)
{
	#echo "aqui";
	print_r($row);
	#$i++;
	break;
}
echo "</pre>-->";*/
/************************** DEBUG **************************/

/**
* DATABASE TABLE SETUP - Setup the Database Table Adapter
* Since our application will be utilizing the Zend_Db_Table component, we need 
* to give it a default adapter that all table objects will be able to utilize 
* when sending queries to the db.
*/
Zend_Db_Table_Abstract::setDefaultAdapter($objDb);

// REGISTRY - setup the application registry
// An application registry allows the application to store application 
// necessary objects into a safe and consistent (non global) place for future 
// retrieval.  This allows the application to ensure that regardless of what 
// happends in the global scope, the registry will contain the objects it 
// needs.
/** Registra o objeto Zend_Db objDb (MSACESS) na mem�ria */
Zend_Registry::set('db', $objDb);
/** Registra o objeto Zend_Db objDbUsers (MSSqlServer) na mem�ria */
Zend_Registry::set('dbUsers', $objDbUsers);
// $registry = Zend_Registry::getInstance();
// $registry->configuration 	= $objConf;
// $registry->db     			= $objDb;

// Carregando arquivo de internacionaliza��o
#include_once 'i18n.php'; 
#Zend_Loader::loadClass('Zend_Translate');
#$translate = new Zend_Translate('array', $portugues, 'pt_BR'); 
#$registry->set('translate', $translate);  

/**
 * 
 * Inicializando o sistema
 */
$objControlador->dispatch();

// Step 5: CLEANUP - Remove items from global scope.
// This will clear all our local boostrap variables from the global scope of 
// this script (and any scripts that called bootstrap).  This will enforce 
// object retrieval through the applications's registry.
unset($objControlador, $objConf, $objDb, $objConfigUsers, $objDbUsers);

/**
 * 
 * N�o se p�e a tag de fechamento do PHP, para evitar mensagens de erros
 */