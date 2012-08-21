/*
* main js for unfollwers app
*/

(function ($) {

	var SearchView = Backbone.View.extend({

		el:$('body'),
		events:{
 		'click input#search_submit': 'check',
 		'click input#search_update': 'update'
 		
		},

	 initialize: function(){
	      _.bindAll(this, 'render', 'check','update'); // every function that uses 'this' as the current object should be in here
	      this.counter = 0; // total number of items added thus far
	      this.render(); 
		},
      render: function(){

   	    },  
      check: function(){

    	twitter_name = $('#twitter_username').val();

    	if(twitter_name != ""){
			//passing value to controller;
			this.loadRestfulData("http://twitter.dev/dev/api/twitter/"+twitter_name);
			
		}else{

			alert('Please insert your twitter user name');

		}

    },	
    loadRestfulData: function(api){

			//Set the content pane to a loading screen
			$("#content-pane").text( "fetching your data..." );

			//Load the data in using jQuerys ajax call
			$.ajax({
				url: api,
				type:'get',
				dataType: "json",
				success: function(data){

					if (data!== null){

					//Once we receive the data, set it to the content pane.
					//console.log(data.length);

					$("#content-pane").text( "Your Latest unfollowers are :" );

						for (var i = 0; i < data.length; i++) {
					
						var html = "<li><a href='http://twitter.com/#!/"+data[i].screen_name+"' target='_blank'><img src="+data[i].profile_image_url+" /><storng>"+data[i].name+"</strong><p>"+data[i].description+"</p></li><br/>";

						$("#content-pane").append(html);

						}
						
					}else{
					
						var html="You are lucky, you have no unfollowers these between your lastest check.";
						$("#content-pane").text(html);

					};

				},
				error:function(){

					alert('Sorry, we cannot content your twitter account? Please make sure you input the right user name!');

				}

			});	
		},

		update:function(){

			alert('hi');
			
		var restful_put_url = "http://twitter.dev/dev/api/twitter/galaxy_watcher";
			$.ajax({
				url: restful_put_url,
				type:'put',
				dataType: "json",
				success: function(data){
				console.log(data);
				}	

			});




 		}
 	});

 	var SearchView = new SearchView();      


})(jQuery);
