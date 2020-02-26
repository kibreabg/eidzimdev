import com.fusionmaps.core.Map;
class com.fusionmaps.maps.EgyptMap extends Map {
	//Version number (if different from super Map class)
	//private var _version:String = "3.0.0";
	//MapId represents the identifier name of the map movie clip
	private var mapId:String = "EgyptMap";
	/**
	* Constructor function. We invoke the super class'
	* constructor and then set the objects for this map.
	*/
	function EgyptMap(targetMC:MovieClip, depth:Number, width:Number, height:Number, x:Number, y:Number, debugMode:Boolean, lang:String ,scaleMode: String, registerWithJS: Boolean, DOMId:String) {
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
		super.addEntity("01", "AL", "AL", "Alexandria");
		super.addEntity("02", "AN", "AN", "Aswan");
		super.addEntity("03", "AT", "AT", "Asyut");
		super.addEntity("04", "BH", "BH", "Beheira");
		super.addEntity("05", "BN", "BN", "Beni Suef");
		super.addEntity("06", "CA", "CA", "Cairo");
		super.addEntity("07", "DA", "DA", "Dakahlia");
		super.addEntity("08", "DM", "DM", "Damietta");
		super.addEntity("09", "FY", "FY", "Faiyum");
		super.addEntity("10", "GH", "GH", "Gharbia");
		super.addEntity("11", "GZ", "GZ", "Giza ");
		super.addEntity("12", "IS", "IS", "Ismailia");
		super.addEntity("13", "KS", "KS", "Kafr el-Sheikh");
		super.addEntity("14", "MT", "MT", "Matruh");
		super.addEntity("15", "MN", "MN", "Minya");
		super.addEntity("16", "MF", "MF", "Monufia");
		super.addEntity("17", "NV", "NV", "New Valley");
		super.addEntity("18", "NS", "NS", "North Sinai");
		super.addEntity("19", "PS", "PS", "Port Said");
        super.addEntity("20", "QA", "QA", "Qalyubia");
        super.addEntity("21", "QE", "QE", "Qena");
        super.addEntity("22", "RS", "RS", "Red Sea");
        super.addEntity("23", "SQ", "SQ", "Sharqia");
        super.addEntity("24", "SH", "SH", "Sohag");
        super.addEntity("25", "SS", "SS", "South Sinai");
		super.addEntity("26", "SZ", "SZ", "Suez");
		super.addEntity("27", "LX", "LX", "Luxor");
		super.addEntity("28", "HW", "HW", "Helwan");
		super.addEntity("29", "SO", "SO", "6th of October");
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



