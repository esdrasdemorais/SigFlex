package model
{
	[Bindable]
	public class ModelLocator
	{
		private static var instance:ModelLocator;
		
		/**
		 *  VO com todos os dados do usuario logado 
		 */
		 
		//public var dadosUsuario:Object = new Object();
		public var dadosUsuario:UsuarioVo = new UsuarioVo();
		
		public function ModelLocator(enforcer:SingletonEnforcer)
		{
			if(enforcer==null)
			{
				throw new Error("[ ModelLocator Error ]: instância inválida.");
			}
		}
		
		public static function getInstance():ModelLocator
		{
			return instance=( instance==null )? new ModelLocator(new SingletonEnforcer()):instance;
		}
	}
}
class SingletonEnforcer{}