(function ($) {
	
	var restfulApp = Backbone.Controller.extend({
		restfulUrl: "http://api.openkeyval.org/", //This is a free service to store key pair values
		
		//Routes tell the app what to do 
		routes: {
		"animals/:animal":          "animalAction",  //This matches app/animals/* and assigns * to a variable called "animal"
		"*page":                 	"defaultAction", //This simply matches any urls that weren't caught above and assigns it to "page"
		},
		
		defaultAction: function( page ){
			if( page ) {
				//Once the default action is called we want to construct a link to our restful service
				var restfulPageUrl = this.restfulUrl + page + "page" //http://api.openkeyval.org/gangsterpage
				//Now we have a url lets get the data
				this.loadRestfulData( restfulPageUrl );
			}
		},
		animalAction: function( animal ) {
			//Once the default action is called we want to construct a link to our restful service
			var restfulPageUrl = this.restfulUrl + animal + "page" //http://api.openkeyval.org/dogpage
			//Now we have a url lets get the data
			this.loadRestfulData( restfulPageUrl );
		},
		loadRestfulData: function( pageUrl ){
			//Set the content pane to a loading screen
			$("#content-pane").text( "loading data..." );
			//Load the data in using jQuerys ajax call
			$.ajax({
				url: pageUrl,
				dataType: "jsonp",
				success: function(data){
					//Once we receive the data, set it to the content pane.
					$("#content-pane").text( data );
				}
			});	
		}

	var app = new restfulApp;
	//Initiate a new history and controller class
	Backbone.emulateHTTP = true;
	Backbone.emulateJSON = true 
	Backbone.history.start();

})(jQuery);

