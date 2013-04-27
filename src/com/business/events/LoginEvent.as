package com.business.events
{
	import com.adobe.cairngorm.control.CairngormEvent;
	import com.vo.UsuarioVO;
	
	import flash.events.Event;
	
	public class LoginEvent extends CairngormEvent
	{
		public static const LOGIN:String = "Login";
		public var userVO:UsuarioVO;
		
		public function LoginEvent(_userVO:UsuarioVO)
		{
			super(LOGIN);
			
			this.userVO = _userVO;
		}
		
		override public function clone():Event
		{
			return new LoginEvent(userVO);	
		}
	}
}