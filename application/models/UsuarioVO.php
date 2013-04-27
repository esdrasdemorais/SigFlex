<?php

class UsuarioVO
{
	/*private $usCodigo 	   = 0;
	private $nsCodigo 	 	   = 0;
	private $usLogin 	 	   = '';
	private $usDrt 		 	   = 0;
	private $usNome 	 	   = '';
	private $usSenha 	 	   = '';
	private $usStatus 	 	   = '';
	private $usCpf 		 	   = '';
	private $usEmail		   = '';
	private $usDtAtualiza 	   = '';
	private $acessPrivilegesVO = null;*/
	
	public $usCodigo 	 	   = 0;
	public $nsCodigo 	 	   = 0;
	public $usLogin 	 	   = '';
	public $usDrt 		 	   = 0;
	public $usNome 	 	       = '';
	public $usSenha 	 	   = '';
	public $usStatus 	 	   = '';
	public $usCpf 		 	   = '';
	public $usEmail		       = '';
	public $usDtAtualiza 	   = '';
	public $acessPrivilegesVO = null;
	
	public function UsuarioVO($row=null)
	{
		if($row)
		{
			$this->setUsCodigo($row['us_codigo']);
			$this->setNsCodigo($row['ns_codigo']);
			$this->setUsLogin($row['us_login']);
			$this->setUsDrt($row['us_drt']);
			$this->setUsNome($row['us_nome']);
			$this->setUsSenha($row['us_senha']);
			$this->setUsStatus($row['us_status']);
			$this->setUsCpf($row['us_cpf']);
			$this->setUsEmail($row['us_email']);
			$this->setUsDtAtualiza($row['us_dtatualiza']);
			/*$this->setAcessPrivilegesVO(is_object($row['acessPrivilegesVO']) ? 
										$row['acessPrivilegesVO'] : new AcessPrivilegesVO());*/
		}
	}
	
	public function setUsCodigo($usCodigo)
	{
		$this->usCodigo = (int) $usCodigo;
	}
	
	public function setNsCodigo($nsCodigo)
	{
		$this->nsCodigo = (int) $nsCodigo;
	}
	
	public function setUsLogin($usLogin)
	{
		$this->usLogin = (string) $usLogin;
	}
	
	public function setUsDrt($usDrt)
	{
	    $this->usDrt = $usDrt;
	}
	
	public function setUsNome($usNome)
	{
	    $this->usNome = (string) $usNome;
	}
	
	public function setUsSenha($usSenha)
	{
	    $this->usSenha = (string) htmlspecialchars($usSenha);
	}
	
	public function setUsStatus($usStatus)
	{
		 $this->usStatus = (string) $usStatus;
	}
	
	/**
	 * @param unknown_type $acessPrivilegesVO
	 */
	public function setAcessPrivilegesVO (AcessPrivilegesVO $acessPrivilegesVO) { $this->acessPrivilegesVO = $acessPrivilegesVO; }

/**
	 * @param unknown_type $usDtAtualiza
	 */
	public function setUsDtAtualiza ($usDtAtualiza) { $this->usDtAtualiza = (string) $usDtAtualiza; }

	/**
	 * @param unknown_type $usEmail
	 */
	public function setUsEmail ($usEmail) { $this->usEmail = (string) $usEmail; }

	public function setUsCpf($usCpf)
	{
	    $this->usCpf = (string) $usCpf;
	}
	
	/**
	 * @return unknown
	 */
	public function getAcessPrivilegesVO () { return $this->acessPrivilegesVO; }

	/**
	 * @return unknown
	 */
	public function getNsCodigo () { return $this->nsCodigo; }

	/**
	 * @return unknown
	 */
	public function getUsCodigo () { return $this->usCodigo; }

	/**
	 * @return unknown
	 */
	public function getUsCpf () { return $this->usCpf; }

	/**
	 * @return unknown
	 */
	public function getUsDrt () { return $this->usDrt; }

	/**
	 * @return unknown
	 */
	public function getUsDtAtualiza () { return $this->usDtAtualiza; }

/**
	 * @return unknown
	 */
	public function getUsEmail () { return $this->usEmail; }

/**
	 * @return unknown
	 */
	public function getUsLogin () { return $this->usLogin; }

/**
	 * @return unknown
	 */
	public function getUsNome () { return $this->usNome; }

/**
	 * @return unknown
	 */
	public function getUsSenha () { return $this->usSenha; }

/**
	 * @return unknown
	 */
	public function getUsStatus () { return $this->usStatus; }
}