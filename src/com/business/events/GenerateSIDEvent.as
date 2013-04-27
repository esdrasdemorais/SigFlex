package com.business.events
{
    import com.adobe.cairngorm.control.CairngormEvent;
    
    import flash.events.Event;
    
    public class GenerateSIDEvent extends CairngormEvent
    {
        public static const NEWSID:String = "NewSID";
        
        public function GenerateSIDEvent():void {
            super(NEWSID);
        }
        
        override public function clone():Event {
            return new GenerateSIDEvent();
        }
    }
}