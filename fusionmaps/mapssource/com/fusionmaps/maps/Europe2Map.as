import com.fusionmaps.core.Map;
class com.fusionmaps.maps.Europe2Map extends Map {
	//Version number (if different from super Map class)
	//private var _version:String = "3.0.0";
	//MapId represents the identifier name of the map movie clip
	private var mapId:String = "Europe2Map";
	/**
	* Constructor function. We invoke the super class'
	* constructor and then set the objects for this map.
	*/
	function Europe2Map(targetMC:MovieClip, depth:Number, width:Number, height:Number, x:Number, y:Number, debugMode:Boolean, lang:String ,scaleMode: String, registerWithJS: Boolean, DOMId:String) {
		//Invoke the super class constructor
		super(targetMC, depth, width, height, x, y, debugMode, lang, scaleMode ,registerWithJS, DOMId);
		//Set the identifier name of Map movie clip
		this.config.mapId = mapId;
	}
	/**
	 * render method renders the chart.
	*/
	public function render():Void {
		//Feed entities for this chart
		this.feedEntities();
		//Just call render of parent method
		super.render();
	}
	/**
	 * feedEntities method feeds the entities of this chart in the entity array.
	 * Each country/state/county/city on the map is stored as an entity with following
	 * properties: id, shortName, longName
	 * For any map, the feedEntities is a MUST. All entities on the map should be fed
	 * in this function.
	*/
	public function feedEntities() {
		super.addEntity("EU.AL", "AL", "AL", "Albania");
		super.addEntity("EU.AD", "AD", "AD", "Andorra");
		super.addEntity("EU.AT", "AT", "AT", "Austria");
		super.addEntity("EU.BY", "BY", "BY", "Belarus");
		super.addEntity("EU.BE", "BE", "BE", "Belgium");
		super.addEntity("EU.BA", "BA", "BA", "Bosnia and Herzegovina");
		super.addEntity("EU.BG", "BG", "BG", "Bulgaria");
		super.addEntity("EU.HY", "HY", "HY", "Croatia");
		super.addEntity("EU.CZ", "CZ", "CZ", "Czech Republic");
		super.addEntity("EU.DK", "DK", "DK", "Denmark");
		super.addEntity("EU.EE", "EE", "EE", "Estonia");
		super.addEntity("EU.FI", "FI", "FI", "Finland");
		super.addEntity("EU.FR", "FR", "FR", "France");
		super.addEntity("EU.DE", "DE", "DE", "Germany");
		super.addEntity("EU.GR", "GR", "GR", "Greece");
		super.addEntity("EU.HU", "HU", "HU", "Hungary");
		super.addEntity("EU.IS", "IS", "IS", "Iceland");
		super.addEntity("EU.IE", "IE", "IE", "Ireland");
		super.addEntity("EU.IT", "IT", "IT", "Italy");
		super.addEntity("EU.LV", "LV", "LV", "Latvia");
		super.addEntity("EU.LI", "LI", "LI", "Liechtenstein");
		super.addEntity("EU.LT", "LT", "LT", "Lithuania");
		super.addEntity("EU.LU", "LU", "LU", "Luxembourg");
		super.addEntity("EU.MK", "MK", "MK", "Macedonia");
		super.addEntity("EU.MT", "MT", "MT", "Malta");
		super.addEntity("EU.MD", "MD", "MD", "Moldova");
		super.addEntity("EU.MC", "MC", "MC", "Monaco");
		super.addEntity("EU.MO", "MO", "MO", "Montenegro");
		super.addEntity("EU.NL", "NL", "NL", "Netherlands");
		super.addEntity("EU.NO", "NO", "NO", "Norway");
		super.addEntity("EU.PL", "PL", "PL", "Poland");
		super.addEntity("EU.PT", "PT", "PT", "Portugal");
		super.addEntity("EU.RO", "RO", "RO", "Romania");
		super.addEntity("EU.SM", "SM", "SM", "San Marino");
		super.addEntity("EU.CS", "CS", "CS", "Serbia");
		super.addEntity("EU.SK", "SK", "SK", "Slovakia");
		super.addEntity("EU.SL", "SL", "SL", "Slovenia");
		super.addEntity("EU.ES", "ES", "ES", "Spain");
		super.addEntity("EU.SE", "SE", "SE", "Sweden");
		super.addEntity("EU.CH", "CH", "CH", "Switzerland");
		super.addEntity("EU.UA", "UA", "UA", "Ukraine");
		super.addEntity("EU.UK", "UK", "UK", "United Kingdom");
		super.addEntity("EU.VA", "VA", "VA", "Vatican City");
		super.addEntity("EU.CY", "CY", "CY", "Cyprus");
		super.addEntity("EU.TK", "TK", "TK", "Turkey");
		super.addEntity("EU.RU", "RU", "RU", "Russia");
		
		/*-----------------SOME ISLANDS-------------*/
		super.addEntity("EU.FD", "FD", "FD", "Faroe Islands(Den)");
		super.addEntity("EU.GI", "GI", "GI", "Gibraltar (UK)");
		super.addEntity("EU.RD", "RD", "RD", "Rhodes (Gr)");
		super.addEntity("EU.GU", "GU", "GU", "Guersey");
		super.addEntity("EU.IM", "IM", "IM", "Isles of Man");
		super.addEntity("EU.JE", "JE", "JE", "Jersey");
		super.addEntity("EU.KO", "KO", "KO", "Kosovo");
		super.addEntity("EU.AF", "AF", "AF", "Aland (Finland)");
		
		
	}
	/**
	 * reInit method re-initializes the map. This method is basically called
	 * when the user changes map data through JavaScript. In that case, we need
	 * to re-initialize the map, set new XML data and again render. 	 
	*/
	public function reInit():Void {
		//Invoke the super class's reInit method.
		super.reInit();
		//Set the identifier name of Map movie clip
		this.config.mapId = mapId;
	}
}
