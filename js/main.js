/*
* main js for unfollwers app
*/
(function ($) {

	 //set up global api url
	 var sAPIurl = location.href+'api/twitter/';


	var SearchView = Backbone.View.extend({
		el:$('body'),
		events:{
 		'click input#search_submit': 'check',
 		'click button#init' :'init'
		},

	 initialize: function(){
	      _.bindAll(this, 'render', 'check','update'); // every function that uses 'this' as the current object should be in here
	      this.counter = 0; // total number of items added thus far
	      this.render(); 
		},
      render: function(){

   	    },  
      check: function(){

      	$('#update_container').hide();

	    	twitter_name = $('#twitter_username').val();

	    	var regex_pat_whitespace = /\s/;


	    	if( regex_pat_whitespace.test(twitter_name) != true){

		    	if(twitter_name != ""){
					//passing value to controller;
					this.loadRestfulData(sAPIurl+twitter_name);
					
				}else{
					bootbox.alert("Please insert your twitter user name!");
				}	
		
		}else{
			bootbox.alert("Please insert your valida user name! You shouldn't have whitespace in your twitter name. ");

		}

   },	

  destroy: function(){
  this.remove();
  this.unbind();
},
  loadRestfulData: function(api){

  			

			//Set the content pane to a loading screen
			  $("#content-pane").Loadingdotdotdot({
                    "speed": 400,
                    "maxDots": 5,
                    "word": "fetching your data"
                });





			//Load the data in using jQuerys ajax call
			$.ajax({
				url: api,
				type:'get',
				dataType: "json",
				success: function(data){

		
 					
				//stop the loading 
				//setTimeout(function() { ("#content-pane").Loadingdotdotdot("Stop"); }, 5000);

				//if no errors
				if ( data!== null && data.error_code === undefined){

					if (data!== null && data.success_code === undefined) {
					//Once we receive the data, set it to the content pane.
					$("#content-pane").text( "Your latest unfollowers are :" );

						var html_wrapper = "<ul>";

						for (var i = 0; i < data.length; i++) {
					
							if(data[i].error == undefined){

							//console.log(data[i]);

							var html = "<li class='unfollowers'>";

							html += "<div class='left'>";

							html +="<a href='http://twitter.com/#!/"+data[i].screen_name+"' target='_blank'>";

							html +="<img src="+data[i].profile_image_url+" /></a></div>";

							html +="<div class='right'><storng class='fullname'>"+data[i].name+" </strong><span>‏</span>";

							html +="<storng class='username'>@"+data[i].screen_name+"</strong>";

							html +="<p class='bio'>";

							html +=data[i].description+"</p></div>";

							html +="</li>";
							
							}else{

								var html = "<li>"+data[i].error+"</li>";
							}

							html_wrapper +="</ul>";

							$("#content-pane").append(html);

							//active update view?
							$('#update_container').fadeIn('slow');

							 this.update_view = new updateView();

						     this.update_view.parentView = this;

						     $(this.el).append(this.update_view.el);


						}

							
					}else if(data.success_code !== null && data.success_code == 101){

						var html="You are lucky, you have no new unfollowers since your lastest check :)";

						$("#content-pane").text(html);

						}
				}else{
					// has error code:
					switch(data.error_code){
						case '404':
						bootbox.alert("The user with the twitter name cannot be found! Please verify your account name.");
						break;

						case '100':
						$("#udpateModal").modal('show');

						break;

						default:
						break;
					}

					//display error message
					//clear this content pane
					$("#content-pane").text('');
				};

				},
				error:function(data){
					bootbox.alert("Sorry, we cannot content your twitter account? Please make sure you input the right user name!");
					var html ="";
					$("#content-pane").text(html);
				}

			});	
		},
	
 		init:function(){
    	twitter_name = $('#twitter_username').val();


		var restful_put_url = sAPIurl+twitter_name;
		//Set the content pane to a loading screen
			  $("#content-pane").Loadingdotdotdot({
                    "speed": 400,
                    "maxDots": 5,
                    "word": "initialize your storge"
                });

			$.ajax({
				url: restful_put_url,
				type:'post',
				dataType: "json",
				success: function(data){

					if(data.error_code == undefined ){
					//hide the modal box 
					$("#udpateModal").modal('hide');
					
					//notify the content message
					$("#content-pane").html("Congratulation, your account storge has been initialized.<br/><br/>Come back later to find out who's the evil unfollowers!");
					
					}else{

					bootbox.alert('Sorry, our server is having issue repsonding your requsts,please try it later.');

					}

				},
				error:function(data){
				console.log(data);	
				bootbox.alert("Sorry, we are having problem with initializing your account, Please try it later");

				}	
		});
 		}

 	});
	
	//init the app
 	var SearchView = new SearchView();     


 	 

})(jQuery);
