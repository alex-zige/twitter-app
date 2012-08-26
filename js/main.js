/*
* main js for unfollwers app
*/

(function ($) {

	var SearchView = Backbone.View.extend({

		el:$('body'),
		events:{
 		'click input#search_submit': 'check',
 		'click button#update' :'update'
 		
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

			bootbox.alert("Please insert your twitter user name");

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

//					console.log(data[0].error);

					if (data!== null){

					//Once we receive the data, set it to the content pane.

					$("#content-pane").text( "Your Latest unfollowers are :" );

						var html_wrapper = "<ul>";

						for (var i = 0; i < data.length; i++) {
					
							if(data[i].error == undefined){

								console.log(data[i]);

							var html = "<li class='unfollowers'>";

							html += "<div class='left'>";

							html +="<a href='http://twitter.com/#!/"+data[i].screen_name+"' target='_blank'>";

							html +="<img src="+data[i].profile_image_url+" /></a></div>";

							html +="<div class='right'><storng class='fullname'>"+data[i].name+" </strong><span>‏</span>";

							html +="<storng class='username'>@"+data[i].screen_name+"</strong>";

							html +="<p class='bio'>";

							html +=data[i].description+"</p></div>";

							html +="</li>";

							//var html_new= html;
							
							}else{
							
							var html = "<li>"+data[i].error+"</li>";

							}


						html_wrapper +="</ul>";

						$("#content-pane").append(html);

						// alert the box for udpate the latest records?
						$("#udpateModal").modal('show');
						

						}

					}else{

						$("#udpateModal").modal('show');
						//live = same
						var html="You are lucky, you have no unfollowers these between your lastest check.";
						$("#content-pane").text(html);

					};

				},
				error:function(){

					bootbox.alert("Sorry, we cannot content your twitter account? Please make sure you input the right user name!");

					var html ="";

					$("#content-pane").text(html);
				}

			});	
		},

		update:function(){
			
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
