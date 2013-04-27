<?php

class Usuario
{
	private $objDb;
	private $objDbSqlServer;
	private $auth;
	
	public function Usuario()
	{
		// Testar se FUNFA
		Zend_Loader::loadClass('Zend_Session');
				
		$objRegistry = Zend_Registry::getInstance();
		
		// Obt�m a Conex�o com a base sigOffice.mdb criada no index.php, bootstrap (inicializa��o da aplica��o)
		$this->objDb = $objRegistry->db;
		
		// Obt�m a Conex�o com a base dbUsuarios (SqlServer) criada no index.php, bootstrap (inicializa��o da aplica��o)
		$this->objDbSqlServer = $objRegistry->dbUsers;		
		
		// Obt�m a refer�ncia a inst�ncia singleton da classe Zend_Auth
		$this->auth = Zend_Auth::getInstance();
	}
	
	/** Thanks to Wade Arnold's article - Zend Amf now with php session support **/
	/** increment the current count session variable and return it's value */
    public function getCount()
    {
    	$_SESSION['count']++;
    	return $_SESSION['count'];
    }

    /** return the php session id value */
    public function getSessionID()
    {
    	return session_id();
    }

    /** Tell's php to generate a new session id */
    public function updateSessionID()
    {
    	return session_regenerate_id();
    }

    /** clear the refrence to the count session variable */
    public function unregister()
    {
    	unset($_SESSION['count']);
    	return true;
    }
	
	public function generateNewSessionID()
	{
		$newSIDStatus = $this->updateSessionID();
		if($newSIDStatus === true)
		{
			return $this->getSessionID();
		}
	}
     
	/**
	*
	* Autentica o usu�rio
	*
	* @todo adiciona rotina para verifica��o usando SSO
	*
	* @return mixed (Exception em caso de erro ou uma inst�ncia da classe UsuarioVO em caso e sucesso
	*
	*/
	public function checkLdapLogin(UsuarioVO $user)
	{
		// importa a classe View Object (VO) de Usu�rio
		Zend_Loader::loadClass('UsuarioVO');
		
		$configLdap = new Zend_Config_Ini('./application/config.ini', 'ldaps');
		
		// Obt�m o Caminho do LOG
		$logPath = $configLdap->ldap->logPath;
		
		$options = $configLdap->ldap->toArray();
		unset($options['logPath']);
		
		try
		{
			$adapter = new Zend_Auth_Adapter_Ldap($options, $user->getUsLogin(), $user->getUsSenha());
			
			// Salva o resultado da autentica��o
			// $result = Zend_Auth::getInstance()->authenticate($adapter);
			/** http://framework.zend.com/manual/en/zend.auth.html#zend.auth.introduction.persistence */
			$auth 	= Zend_Auth::getInstance();
			$result = $auth->authenticate($adapter);
			
			if($logPath)
			{
				$messages = $result->getMessages();	
				$this->setUsuarioLog($logPath, $messages, $auth);
			}

			/**
			 * Obt�m os dados do usu�rio para Sincroniza��o com os dados da tabela tb_usuarios
			 * (base dbUsuarios SQL Server), caso o usu�rio tenha sido autenticado com sucesso no LDAP (AD)
			 **/
			if($result->isValid() && $result->getCode() == Zend_Auth_Result::SUCCESS)
			{
				$sql = "SELECT * ";
				$sql.= "FROM tb_usuarios ";
				$sql.= "WHERE us_login = '".$user->getUsLogin()."' ";
				$sql.= "AND us_status = 'Ativo'";
				$res = $this->objDbSqlServer->fetchRow($sql);
				
				// Caso o usu�rio j� exista na tabela tb_usuarios (base dbUsuarios SQL) atualiza a senha
				if(count($res))
				{
					$row = $res;
					$row['us_senha'] = $user->getUsSenha();
					
					$sql = "UPDATE tb_usuarios ";
					$sql.= "SET us_senha = '".$user->getUsSenha()."' ";
					$sql.= "WHERE us_login = '".$user->getUsLogin()."' ";
					$sql.= "AND us_status = 'Ativo'";
					$res = $this->objDbSqlServer->query($sql);
					
					if($res)
					{
						$usuarioVO = new UsuarioVO($row);
					}
					else
					{
						throw new Exception("Ocorreu um Erro ao Atualizar a Senha do Usu�rio.");
					}
				}
				else
				{
					// Salva os dados do novo usu�rio (base MSACESS) na base de usu�rios dbUsuarios (SQL Server)
					$sql = "SELECT usuario, drt, nome, Email ";
					$sql.= "FROM Funcionario ";
					$sql.= "WHERE usuario = '".$user->getUsLogin()."' ";
					$sql.= "AND ativo = 1";
					$res = $this->objDb->fetchRow($sql);
					
					if(count($res))
					{
						$sql = "INSERT INTO tb_usuarios(";
						$sql.= "us_login, us_drt, us_nome, ";
						$sql.= "us_email, us_status, us_dtatualiza";
						$sql.= ")VALUES(";
						$sql.= "'".$res['usuario']."', ".$res['drt'].", '".$res['nome']."', ";
						$sql.= "'".$res['Email']."', 'Ativo', NOW()";
						$sql.= ")";
						if($this->objDbSqlServer->query($sql))
						{
							$usuarioVO = new UsuarioVO($res);
						}
						else
						{
							throw new Exception("Ocorreu um Erro ao Salvar o Novo Usu�rio.");
						}
					}
					else
					{
						throw new Exception("Funcion�rio n�o cadastrado.");
					}
				}
			}
			else
			{
				$strMessage = '';
				// Falha de autentica��o				
				switch($result->getCode())
				{
					case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
						$strMessage .= "\nUsu�rio n�o encontrado.";
					break;
					
					case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
						$strMessage .= "\nSenha incorreta.";
					break;
					
					case Zend_Auth_Result::FAILURE:
						$strMessage .= "\nFalha na Autentica��o Desconhecida.";
					break;
					
					case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
						$strMessage .= "\nUsu�rio Duplicado.";
					break;
					
					case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
						$strMessage .= "\nFalha n�o Categorizada.";
					break;
					
					default:
						$strMessage .= "Erro Interno! Caso este problema persistir, favor contate o administrador de rede.";
					break;
				}
				
				throw new Exception($strMessage);
			}
			
			// Retorna as Permiss�es do Usu�rio (Zend_ACL)
			$usuarioVO->setAcessPrivilegesVO($this->getPermissions($usuarioVO));
			
			return $usuarioVO;
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage(). " - " . $e->getTrace());
		}
	}
	
	// Salvar Log de Autentica��o via LDAP (diret�rio localizado no config.ini -> /tmp/ldap.log)
	private function setUsuarioLog($logPath, array $messages, $auth = null)
	{
		//$logPath = 'php://output';
		
		$logger = new Zend_Log();
		#$logger->addWriter(new Zend_Log_Writer_Stream($logPath));
		#$filter = new Zend_Log_Filter_Priority(Zend_Log::DEBUG);
		#$logger->addFilter($filter);
		
		foreach($messages as $i => $message)
		{
			if($i-- > 1)
			{
				$message = str_replace("\n", "\n ", $message);
				#$logger->log(
				#	"Ldap: ".$i.": Identity(user):".$auth->getIdentity()."=".$message, 
				#	Zend_Log::DEBUG
				#);
			}
		}
		
		$logger->setEventItem('\n pid', getmypid());
	}
	
	// Obt�m as permiss�es do usu�rio autenticado
	public function getPermissions(UsuarioVO $user)
	{
		// Necess�rio retornar a role dos usu�rios autenticados, isto ser� passado dentro da Zend_Acl
		// Zend_Auth_Adapter_DbTable->getResultRowObject retorna um objeto stdClass ent�o � preciso to dereference the role in this manner
		// $r			= $auth->getResultRowObject(array('role'));
		// $userRole 	= $r->role;

		// AcessPrivilegesVO para Mapear os Privil�gios do usu�rio
		$userRolePrivileges = new AcessPrivilegesVO();
		
		// Generate a new sessionID if the session already exists
		if(Zend_Session::sessionExists())
		{
			$this->updateSessionID();
		}
		
		//$this->sessionid = $this->getSessionID();
		$userRolePrivileges->sessionID = $this->getSessionID();
		
		// setando o objeto ACL (Access Control List)
		$objAcl = new Zend_Acl();
		
		// Adiciona grupos ao registro de Role usando Zend_Acl_Role
		$objAcl->addRole(new Zend_Acl_Role('public'));
		$objAcl->addRole(new Zend_Acl_Role('manager'), 'public');
		$objAcl->addRole(new Zend_Acl_Role('admin'), 'manager');
		
		// Administrador n�o possui hierarquia de controle de acesso, todo o acesso � concedido
		$objAcl->addRole(new Zend_Acl_Role('Super'));
		
		// Configura os recursos dos privil�gios
		$objAcl->add(new Zend_Acl_Resource('viewPublicUI'));
		$objAcl->add(new Zend_Acl_Resource('viewRestrictedUI'));
		$objAcl->add(new Zend_Acl_Resource('viewLogs'));
		$objAcl->add(new Zend_Acl_Resource('createManager'));
		
		// Public pode visualizar somente interfaces p�blicas (Usu�rio Apenas L�)
		$objAcl->allow('public', null, 'viewPublicUI');
		
		// Manager herda o privil�gio viewPublicUI da role public, al�m de privil�gios adicionais
		$objAcl->allow('manager', null, array('viewRestrictedUI')); 
		
		// Admin herda o privil�gio viewRestrictedUI privilege da role manager, mas tamb�m necessita de privil�gios adicionais
		$objAcl->allow('admin', null, array('createManager'));
		
		// Super herda nada, mas lhe � permitido todos os privil�gios
		$objAcl->allow('Super');
		
		$userRoles = $this->getUserRoles($user);
		
		// Verifica as Permiss�es do Usu�rio
		foreach($userRoles as $userRole)
		{
			// Administradores ou Usu�rios do Grupo Compras (101)
			if($userRole['gp_codigo'] == 1 || $userRole['gp_codigo'] == 101)
			{
				// Provis�rio, concluir Permiss�es por Grupo
				$userRole = 'admin';
				$userRolePrivileges->userRole = $userRole;
			
				// Permiss�o Somente Leitura
				$userRolePrivileges->viewPublicUI = $objAcl->isAllowed($userRole, null, 'viewPublicUI') ? 
													"allowed" : "denied";
				
				// Permiss�o Total Somente para Usu�rio Restrito
				$userRolePrivileges->viewRestrictedUI = $objAcl->isAllowed($userRole, null, 'viewRestrictedUI') ?
				 										"allowed" : "denied";
				
				// Permiss�o Total Somente
				$userRolePrivileges->createManager = $objAcl->isAllowed($userRole, null, 'createManager') ?
													 "allowed" : "denied";
				
				$userRolePrivileges->viewLogs = $objAcl->isAllowed($userRole, null, 'viewLogs') ? 
												"allowed" : "denied";
				
				// ... Finalizar Permiss�es por Grupo ...
				//$userRolesPrivileges[] = $userRolePrivileges;
				break;
				// ... Finalizar Permiss�es por Grupo ...
			}
		}
		
		return $userRolePrivileges;
	}
	
	public function getUserRoles(UsuarioVO $user)
	{
		$sql = "SELECT GPU.gp_codigo, GP.gp_descricao ";
		$sql.= "FROM tb_grupos_usuarios AS GPU ";
		$sql.= "INNER JOIN tb_grupos AS GP ";
		$sql.= "	ON GPU.gp_codigo = GP.gp_codigo ";
		$sql.= "WHERE GPU.us_codigo = " . $user->getUsCodigo();
		$res = $this->objDbSqlServer->query($sql);

		$userRoles = array();
		foreach($res as $row)
		{
			$userRoles[] = array(
				'gp_codigo' => $row['gp_codigo'], 
				'gp_descricao' => $row['gp_descricao']
			);
		}
		
		return $userRoles;
	}
}