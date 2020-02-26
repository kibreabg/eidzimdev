import com.fusionmaps.core.Map;
class com.fusionmaps.maps.RussiaMap extends Map {
	//Version number (if different from super Map class)
	//private var _version:String = "3.0.0";
	//MapId represents the identifier name of the map movie clip
	private var mapId:String = "RussiaMap";
	/**
	* Constructor function. We invoke the super class'
	* constructor and then set the objects for this map.
	*/
	function RussiaMap(targetMC:MovieClip, depth:Number, width:Number, height:Number, x:Number, y:Number, debugMode:Boolean, lang:String ,scaleMode: String, registerWithJS: Boolean, DOMId:String) {
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
		super.addEntity("RU.AD", "AD", "AD", "Adygaya");
		super.addEntity("RU.AL", "AL", "AL", "Altai");
		super.addEntity("RU.AM", "AM", "AM", "Amur");
		super.addEntity("RU.AR", "AR", "AR", "Arkhangelsk");
		super.addEntity("RU.AS", "AS", "AS", "Astrakhan");
		super.addEntity("RU.BK", "BK", "BK", "Bashkortostan");
		super.addEntity("RU.BL", "BL", "BL", "Belgorod");
		super.addEntity("RU.BR", "BR", "BR", "Bryansk");
		super.addEntity("RU.BU", "BU", "BU", "Buryatia");
		super.addEntity("RU.CN", "CN", "CN", "Chechnya");
		super.addEntity("RU.CL", "CL", "CL", "Chelyabinsk");
		super.addEntity("RU.CK", "CK", "CK", "Chukotka");
		super.addEntity("RU.CV", "CV", "CV", "Chuvashia");
		super.addEntity("RU.DA", "DA", "DA", "Dagestan");
		//super.addEntity("RU.GA", "GA", "GA", "Gorno-Altay");
		super.addEntity("RU.IN", "IN", "IN", "Ingushetia");
		super.addEntity("RU.IK", "IK", "IK", "Irkutsk");
		super.addEntity("RU.IV", "IV", "IV", "Ivanovo");
		super.addEntity("RU.JE", "JE", "JE", "Jewish Autonomous Region");
		super.addEntity("RU.KB", "KB", "KB", "Kabardino-Balkaria");
		super.addEntity("RU.KN", "KN", "KN", "Kaliningrad");
		super.addEntity("RU.KL", "KL", "KL", "Kalmykia");
		super.addEntity("RU.KG", "KG", "KG", "Kaluga");
		super.addEntity("RU.KQ", "KQ", "KQ", "Kamchatka");
		super.addEntity("RU.KC", "KC", "KC", "Karachay-Cherkessia");
		super.addEntity("RU.KI", "KI", "KI", "Karelia");
		super.addEntity("RU.KE", "KE", "KE", "Kemerovo");
		super.addEntity("RU.KH", "KH", "KH", "Khabarovsk");
		super.addEntity("RU.KK", "KK", "KK", "Khakassia");
		super.addEntity("RU.KM", "KM", "KM", "Khantia-Mansia");
		super.addEntity("RU.KV", "KV", "KV", "Kirov");
		super.addEntity("RU.KO", "KO", "KO", "Komi");
		super.addEntity("RU.KT", "KT", "KT", "Kostroma");
		super.addEntity("RU.KD", "KD", "KD", "Krasnodar");
		super.addEntity("RU.KX", "KX", "KX", "Krasnoyarsk");
		super.addEntity("RU.KU", "KU", "KU", "Kurgan");
		super.addEntity("RU.KS", "KS", "KS", "Kursk");
		super.addEntity("RU.LN", "LN", "LN", "Leningrad");
		super.addEntity("RU.LP", "LP", "LP", "Lipetsk");
		super.addEntity("RU.MG", "MG", "MG", "Magadan");
		super.addEntity("RU.ME", "ME", "ME", "Mariy-El");
		super.addEntity("RU.MR", "MR", "MR", "Mordovia");
		super.addEntity("RU.MC", "MC", "MC", "Moscow City");
		super.addEntity("RU.MS", "MS", "MS", "Moscow");
		super.addEntity("RU.MM", "MM", "MM", "Murmansk");
		super.addEntity("RU.NN", "NN", "NN", "Nenetsia");
		super.addEntity("RU.NZ", "NZ", "NZ", "Nizhegorod");
		super.addEntity("RU.NO", "NO", "NO", "North Ossetia");
		super.addEntity("RU.NG", "NG", "NG", "Novgorod");
		super.addEntity("RU.NS", "NS", "NS", "Novosibirsk");
		super.addEntity("RU.OM", "OM", "OM", "Omsk"); 
		super.addEntity("RU.OL", "OL", "OL", "Oryal");
		super.addEntity("RU.OB", "OB", "OB", "Orenburg");
		super.addEntity("RU.PZ", "PZ", "PZ", "Penza");
		super.addEntity("RU.PE", "PE", "PE", "Perm");
		super.addEntity("RU.PR", "PR", "PR", "Primorsky");
		super.addEntity("RU.PS", "PS", "PS", "Pskov");
		super.addEntity("RU.RO", "RO", "RO", "Rostov");
		super.addEntity("RU.RZ", "RZ", "RZ", "Ryazan");
		super.addEntity("RU.SP", "SP", "SP", "St.Petersburg City");
		super.addEntity("RU.SK", "SK", "SK", "Sakha");
		super.addEntity("RU.SL", "SL", "SL", "Sakhalin");
		super.addEntity("RU.SA", "SA", "SA", "Samara");
		super.addEntity("RU.SR", "SR", "SR", "Saratov");
		super.addEntity("RU.SM", "SM", "SM", "Smolensk");
		super.addEntity("RU.ST", "ST", "ST", "Stavropol");
		super.addEntity("RU.SV", "SV", "SV", "Sverdlovsk");
		super.addEntity("RU.TB", "TB", "TB", "Tambov");
		super.addEntity("RU.TT", "TT", "TT", "Tatarstan");
		super.addEntity("RU.TO", "TO", "TO", "Tomsk");
		super.addEntity("RU.TL", "TL", "TL", "Tula");
		super.addEntity("RU.TU", "TU", "TU", "Tuva");
		super.addEntity("RU.TV", "TV", "TV", "Tver");
		super.addEntity("RU.TY", "TY", "TY", "Tyumen");
		super.addEntity("RU.UD", "UD", "UD", "Udmurtia");
		super.addEntity("RU.UL", "UL", "UL", "Ulyanovsk");
		super.addEntity("RU.VL", "VL", "VL", "Vladimir");
		super.addEntity("RU.VG", "VG", "VG", "Volgograd");
		super.addEntity("RU.VO", "VO", "VO", "Vologda");
		super.addEntity("RU.VR", "VR", "VR", "Voronezh");
		super.addEntity("RU.YN", "YN", "YN", "Yamalia");
		super.addEntity("RU.YS", "YS", "YS", "Yaroslavl");
		//super.addEntity("RU.YV", "YV", "YV", "Yevrey");
		super.addEntity("RU.ZB", "ZB", "ZB", "Zabaykalsk");
		
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
