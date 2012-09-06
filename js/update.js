
var sAPIurl = location.href+'api/twitter/';

updateView = Backbone.View.extend({
   
   el: $('#update_container'),
   events:{
 		'click button#update' :'update',
 		'mouseenter a#whatsit' : 'show_popover',
 		'mouseout a#whatsit' : 'hide_popover'
		},

	initialize:function() {
	   this.render();
	},
	render:function(){

	 var variables = { search_label: "this is a tester" };

	 var template = _.template($("#tpl-update-panel").html(),variables);

		this.el.html(template);

	},
	update:function(){
		//get the twitter name
    	twitter_name = $('#twitter_username').val();
 
		var restful_put_url = sAPIurl+twitter_name;


			$.ajax({
				url: restful_put_url,
				type:'put',
				dataType: "json",
				success: function(data){

				if(data !== undefined && data.success_code == '202'){ 
				
				bootbox.alert("<div style='text-align:center'>Your live storage has been successfully updated! </br> Come back to check your unfollowers later.</div>");

				}else{

				if(data.error_code == '300'){ 

				bootbox.alert("Sorry, We are currently having problem update your followers list! Please try it later.");
		
						}

				}
			}	

			});
 		},
 	show_popover:function(){

 		$('#whatsit').popover('show');

 	},
 	hide_popover:function(){

 		$('#whatsit').popover('hide');

 	}
});