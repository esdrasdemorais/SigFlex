package com.vo
{
	import com.adobe.cairngorm.vo.IValueObject;
	
	[Bindable] [RemoteClass(alias="UsuarioVO")]
	public class UsuarioVO implements IValueObject
	{
		public var usCodigo:int = 0;
		public var usLogin:String;
		public var usDrt:int = 0;
		public var usNome:String = '';
		public var usSenha:String;
		public var usStatus:String = '';
		public var usCpf:String	= '';
		public var usEmail:String = '';
		public var usDtAtualiza:String = '';
		public var acessPrivilegesVO:Object = null;

		/*public function UsuarioVO(_usLogin:String, _usSenha:String)
		{
			this.usLogin = _usLogin;
			this.usSenha = _usSenha;
		}*/
				
		public function UsuarioVO() {}
	}
}