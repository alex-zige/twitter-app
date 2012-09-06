
var sAPIurl = location.href+'api/twitter/';

updateView = Backbone.View.extend({
   
   el: $('#update_container'),
   events:{
 		'click button#update' :'update'
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
 
		var restful_put_url = sAPIurl.twitter_name;
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