package com.business.controls
{
    import com.adobe.cairngorm.control.FrontController;
    import com.business.commands.*;
    import com.business.events.*;

    /**
    * Controller
    * Mappings of Cairngorm Events to Cairngorm commands
    **/
    public class Controller extends FrontController
    {
	    /**
	    * Class constructor
	    * @param none
	    **/
	    public function Controller()
	    {
	    	this.initialize();
	    }

	    /**
	    * Initializes the mappings
	    * @param none
	    * @return void
	    **/
	    public function initialize():void
	    {
		    //ADD COMMANDS
		    this.addCommand(LoginEvent.LOGIN,LoginCommand);
		    this.addCommand(GenerateSIDEvent.NEWSID,GenerateSIDCommand);
	    }
    }
}
