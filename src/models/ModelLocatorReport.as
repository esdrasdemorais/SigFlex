package models
{
	import mx.collections.ArrayCollection;
	
	[Bindable]
	public class ModelLocatorReport
	{
		private static var instance:ModelLocatorReport;
		
		public var dados:ArrayCollection = new ArrayCollection();
		
		public function ModelLocatorReport(singleton:Singleton)
		{
			if(singleton == null)
			{
				throw new Error("[ModelLocatorReport Error] : Instância inválida, classe não pode ser instanciada. Utilize o método desta classe chamado getInstance()");
			}
		}
		
		public static function getInstance():ModelLocatorReport
		{
			return instance = (instance != null) ? instance : new ModelLocatorReport(new Singleton()); 
		}
	}
}
class Singleton{}