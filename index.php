<?php
/**
 * 
 * Sessão de configuração de paths e erros
 */

/** Configura as mensagens de erro que devem ser apresentadas para mostrar os erros apenas nos testes (precisa estar setado no PHP.ini) */
error_reporting(E_ALL|E_STRICT);

/** Seta o timezone pra são paulo (>=PHP 5.1) */
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

/*// carrega classe que fará a inicialização do Zend Framework
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
 * Este include é OBRIGATÓRIO.
 * Zend_Loader carrega arquivos, classes e recursos
 * dinamicamente em sua aplicação PHP.
 * => suporta autocarregamento da SPL (Standard PHP Library)
 * => suporta include_path
 * => fornece mecanismo de falha baseado em exceção
 */
require_once("library/Zend/Loader.php");

/**
 * O método loadClass é responsável por incluir o arquivo responsável pela classe.
 * O acesso a níveis dos diretórios do framework ZEND é feito através do "_" não da "/".
 */

/**
 * 
 * Carregando classes necessárias
 */
 
/**
* O registro (Zend_Registry) é um contâiner para armazenar objetos e valores
* no espaço da aplicação. Armazenar um objeto ou valor
* no registro torna o mesmo sempre disponível ao longo
* da aplicação durante o tempo de vida da requisição.
* Este mecanismo é freqüentemente uma alternativa aceitável
* ao uso de variáveis globais.
* => fornece armazenamento acessível globalmente para objetos
* e valores
* => fornece os padrões iterator, array e indexed access
*/
Zend_Loader::loadClass('Zend_Registry');

Zend_Loader::loadClass('Zend_Controller_Front');  	/** Classe de controladores */
Zend_Loader::loadClass("Zend_View"); 				/* Classe das visões */
Zend_Loader::loadClass('Zend_Config_Ini'); 			/** Classe usada para configurações */
Zend_Loader::loadClass('Zend_Db'); 					/** Classe para acesso a base de dados */
Zend_Loader::loadClass('Zend_Db_Table'); 			/** Classe para usar as tabelas como objetos */
Zend_Loader::loadClass('Zend_Filter_Input');		/** Classe usada para filtrar os dados */

Zend_Loader::loadClass('Zend_Session'); 			/** Inclui o suporte a sessões. Só é necessário caso seja usado. */
Zend_Loader::loadClass('Zend_Session_Namespace'); 	/** Classe usada para armazenar e recuperar dados da sessão */
Zend_Loader::loadClass("Zend_Controller_Plugin_Abstract");
//Zend_Loader::loadClass("SecurityPlugin");
Zend_Loader::loadClass("Zend_Auth");

Zend_Loader::loadClass('Zend_Form');
#Zend_Loader::loadClass('LoginForm');

/** O método set é responsável por armazenar variáveis que podem ser usadas
 * pelos aplicativos. Aqui, registrando os arrays post e get com dados vindos do usuário.
 * o Zend_Filter limpa os dados.
 */
Zend_Registry::set('post', new Zend_Filter_Input(NULL,NULL,$_POST));
Zend_Registry::set('get', new Zend_Filter_Input(NULL,NULL,$_GET));

/** Inicia a sessão global */
Zend_Session::start();

/** Cria o manipulador da dessão */
Zend_Registry::set('session', new Zend_Session_Namespace());

/** Parte das visões (Views) */
$objView = new Zend_View(); /** Cria um novo objeto do tipo view */
$objView->setEncoding('UTF-8'); /** Configura a codificação das páginas */
$objView->setEscape('htmlentities'); /** Escapar entradas HTML */
$objView->setBasePath(APPLICATION_PATH.'/application/views/');	/** Define o diretório onde estarão as visões */
$objView->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper'); /** Adiciona a lib js Dojo ao objeto View */
Zend_Registry::set('view', $objView); 			/** Registra na memória a variável view que indica a visão */

/**
* Este método tenta carregar o arquivo passado como parâmetro procurando no path
* definido com a função set_include_path. Caso ele não consiga encontrar o arquivo,
* é gerada uma exceção que indica arquivo inexistente ou sem acesso. O método
* considera os underscores no nome do arquivo como subdiretórios. Por exemplo, o
* comando Zend::loadClass('Zend_Controller_Front') faz a importação do arquivo
* ../zendframework/library/Zend/Controller/Front.php.
* Seguir esse padrão facilita o entendimento da estrutura do projeto.
* Essa classe se encontra em Zend/Controller/Front.php
* Pode ser "loadado" diretamente pelo nome se preferir
* Para começar nós precisamos "loadar" primeiro o front controller
* Ele faz um controle automático para detectar a base URL e fazer o redirecionamento correto
*/
$objControlador = Zend_Controller_Front::getInstance();

/** Configura o controlador do projeto.
 * O Controlador, por acaso, é o index.php
 */
$baseUrl = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/index.php'));

/** Configura o endereço do controlador do projeto */
$objControlador->setbaseUrl($baseUrl);

/* Mostrar exceções (apenas para testes) */
$objControlador->throwExceptions(TRUE);

// Step 3: CONTROLLER DIRECTORY SETUP - Point the front controller to your action
// controller directory.
#$objControlador->setControllerDirectory('./application/controllers'); // seta diretório com nossos controllers
$objControlador->setControllerDirectory(APPLICATION_PATH.'/controllers'); // seta diretório com nossos controllers

// Step 4: APPLICATION ENVIRONMENT - Set the current environment.
// Set a variable in the front controller indicating the current environment --
// commonly one of development, staging, testing, production, but wholly
// dependent on your organization's and/or site's needs.
#$objControlador->setParam('env', APPLICATION_ENVIRONMENT);
#$objControlador->registerPlugin(new SecurityPlugin());

/** Configura a conexão com a base de dados, pegando as variáveis do arquivo de configuração. */
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
	 * Configurações da diretiva [database] referente a base de dados.
	 * Indica onde estão as configurações do projeto.
	 * Estão no arquivo config.ini na seção (diretiva) database.
	 *
	 */
	$objConfig = new Zend_Config_Ini('./application/config.ini', APPLICATION_ENVIRONMENT); //'database'
	#$objConfig = new Zend_Config_Ini(APPLICATION_PATH.'\config.ini', APPLICATION_ENVIRONMENT); //'database'
	
	/** Registra na memória o objeto Zend_Config_Ini config */
	Zend_Registry::set('config', $objConfig);
	
	// Objeto de Conexão com a base Acess sigOffice.mdb - Zend_Db::factory(driver, arrayParametrosConexao)
	$objDb = Zend_Db::factory($objConfig->db->adapter, $objConfig->db->config->toArray());
	/**************************************** MSACCESS *****************************************************/
	
	/******************************************** MS SQL Server ************************************************/
	// Diretiva com os parâmetros de conexão com a base dbUsuarios do SQL Server
	$objConfigUsers = new Zend_Config_Ini('./application/config.ini', APPLICATION_ENVIRONMENT_USUARIOS); 
	
	/** Registra na memória o objeto Zend_Config_Ini config SqlServer */
	Zend_Registry::set('config', $objConfigUsers);
	
	// Config do SQL Server base dbUsuarios
	$objDbUsers = Zend_Db::factory($objConfigUsers->db->adapter, $objConfigUsers->db->config->toArray());
	
	// Objeto de Conexão com a base Sql Server dbUsuarios
	
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
/** Registra o objeto Zend_Db objDb (MSACESS) na memória */
Zend_Registry::set('db', $objDb);
/** Registra o objeto Zend_Db objDbUsers (MSSqlServer) na memória */
Zend_Registry::set('dbUsers', $objDbUsers);
// $registry = Zend_Registry::getInstance();
// $registry->configuration 	= $objConf;
// $registry->db     			= $objDb;

// Carregando arquivo de internacionalização
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
 * Não se põe a tag de fechamento do PHP, para evitar mensagens de erros
 */