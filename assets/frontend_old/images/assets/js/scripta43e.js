(function($) {

	Drupal.behaviors.mygov_submission_show = {
		attach : function(context, settings) {
		  jQuery( "#edit-field-is-user-pledged-und" ).click(function() {
		    if (jQuery(this).is(":checked") ) {
			  jQuery(this).parent().append('<span class="page-loading">Accepting...</span>');
			  //jQuery('#block-mygov-profiles-mygov-profile-pledge .pledge').hide();
			  //jQuery('#block-mygov-profiles-mygov-profile-pledge .content').html('<span class="pledged">I Pledged</span>');
		      jQuery('#mygov-profiles-plegde-form').submit();
		    }
		  });
		    jQuery( "#edit-field-blue-green-pledge-und" ).click(function() {
		    if (jQuery(this).is(":checked") ) {
			  jQuery(this).parent().append('<span class="page-loading">Accepting...</span>');
			  //jQuery('#block-mygov-profiles-mygov-profile-pledge .pledge').hide();
			  //jQuery('#block-mygov-challenge-blue-green-pledge-block .content').html('<span class="pledged">I Pledged to Segregate</span>');
		      jQuery('#mygov-blue-green-plegde-form').submit();
		    }
		  });
		}
	};


}(jQuery));
jQuery(document).ready(function(){
  jQuery("#block-views-exp-volunteers-page #edit-field-user-full-name-value, #views-exposed-form-volunteers-page-1 #edit-field-user-full-name-value, .views-exposed-form #edit-combine").attr('placeholder', 'Search Participants');
  jQuery(".node-media h2").css('display', 'none');
});
jQuery(document).ready(function(){
  jQuery(".view-id-ulbward #edit-field-state-ulb-tid, #views-exposed-form-ulbward-page #edit-term-node-tid-depth").attr('placeholder', 'Search Here');
  jQuery(".node-media h2").css('display', 'none');
  jQuery('.view-sulb .views-field-name a').attr('title', 'Click here to view state ulb ');
  jQuery('#views-exposed-form-ulbward-page #edit-submit-ulbward ').attr('title', 'Click here');
  jQuery('.current_state_ulb .stateulb_close li a').click(function(){
		jQuery('.current_state_ulb .stateulb_close li a').removeClass('active');
		jQuery(this).addClass('active');
	});

});
jQuery(document).ready(function() {
   var numItems = jQuery('.view-user-activities .views-row').length
       if(numItems==1) {
       jQuery(".view-user-activities .views-row-1").addClass("demoClassAddNew");
   }
});

// add active class on display type changer
jQuery("#display_type_changer a").click(function() {
  jQuery("#display_type_changer a").removeClass('active');
    jQuery(this).addClass('active');
});



// reset button compaign_filter on activity page
function removevalue(){
   //jQuery(".compaign_filter :text").val("");
   //jQuery(".form-item-title :text").val("");
   //jQuery(".compaign_filter #compaign_select").val('All').trigger('change');
   //jQuery('.filter_value input:radio:first-child').attr('checked',true);
   //jQuery(".views-submit-button input").click();
   location.reload(true);
}
// reset button SBM Ministry on SBM page
function removevalue_ministry(){
   jQuery(".sbm_ministry_filter :text").val("");
   jQuery("#views-exposed-form-sbm-xml-data-page #edit-title,#views-exposed-form-sbm-xml-data-page #edit-activity-period,#views-exposed-form-sbm-xml-data-page-1 #edit-activity-period").val("");
   jQuery(".sbm_ministry_filter #sbm_ministry").val('All').trigger('change');
   jQuery(".sbm_ministry_filter #sbm_pakhwada").val('All').trigger('change');
}
