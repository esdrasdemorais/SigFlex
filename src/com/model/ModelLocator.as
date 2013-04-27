package com.model
{
	import com.adobe.cairngorm.CairngormError;
	import com.adobe.cairngorm.CairngormMessageCodes;
	import com.adobe.cairngorm.model.IModelLocator;
	import com.vo.UsuarioVO;
	
	import mx.collections.ArrayCollection;

	[Bindable]
	public class ModelLocator implements IModelLocator
	{
		// Single Instance of Our ModelLocator
		private static var __instance:ModelLocator = null;
		
		/**
		 *  VO com os dados do usuario logado 
		 */
		//public var dadosUsuario:Object = new Object();
		//public var dadosUsuario:UsuarioVO = new UsuarioVO();
		
		public function ModelLocator(__enforcer:SingletonEnforcer)
		{
			if(__enforcer == null)
			{
				//throw new Error("[ ModelLocator Error ]: instância inválida.");
				throw new CairngormError(CairngormMessageCodes.SINGLETON_EXCEPTION, "[ ModelLocator Error ]: instância inválida!"); 
			}
		}
		
		// Returns the Single Instance
		public static function getInstance():ModelLocator
		{
			return __instance = (__instance == null) ? new ModelLocator(new SingletonEnforcer()) : __instance;
		}
		
		//DEFINE YOUR VARIABLES HERE
		public var workflowState:uint = LOGIN;
		public var CURRENT_USER_ROLE:String = "";
		public var CURRENTSESSIONID:String = "";
		public var MAINNAV:ArrayCollection = new ArrayCollection();
		public var admingranted:Boolean = false;
		public var supergranted:Boolean = false;
		public var usuarioVO:UsuarioVO = null;
		
		// DEFINE VIEW CONSTANTS
		public static const LOGIN:uint = 0;
		public static const CONTROLPANEL:uint = 1;
		public static const PUBLIC:uint = 2;
	}
}
// Utility Class to Deny Access to Constructor
class SingletonEnforcer{}