/*******************************************************************************
 * Toggle Behaviour user head ==================================================
 ******************************************************************************/
(function($) {
	Drupal.behaviors.upper_header = {
		attach : function(context, settings) {
			jQuery('.header-top-content #block-views-my-details-block').once("block-common-utils",
					function() {
						jQuery(this).click(function() {
							jQuery('#notification_user_menu').slideToggle();
						});
					});
				$('html,#pledge_link').click(function(){
					$('#notification_user_menu').slideUp();
				})
				$('.header-top-content #block-views-my-details-block').click(function(e){
					e.stopPropagation();
				 });
		}
	}
}(jQuery));

(function($) {
	Drupal.behaviors.upper_header1 = {
		attach : function(context, settings) {
			jQuery('#block-block-30 p').once("block-block-30", function() {
				jQuery(this).click(function() {
					jQuery('#main-menu .menu').slideToggle();
				});
			});
			
		}
	}
}(jQuery));

(function(jQuery) {
	Drupal.behaviors.view_challenges = {
		attach : function(context, settings) {

			jQuery(
					'.challenge-info,.homepage-pinboard .share-icon,.get_inspired-info .share-icon,.social_area_swachhbhawan')
					.hover(
							function(event) {
								jQuery(this).find('.social-area').stop(true,
										true).fadeIn();
							},
							function(event) {
								jQuery(this).find('.social-area').stop(true,
										true).fadeOut();

							});
			jQuery('.view-my-activities-and-challenges .views-field').find(
					'iframe').parent('.right-side-video1').addClass('cm');
			jQuery('.right-side-novideo').parent('.rv').addClass('no-border');

		}
	};
})(jQuery);

jQuery(function() {
	jQuery('.header_top_right_text1 a[href*=#]:not([href=#])').click(
			function() {
				if (location.pathname.replace(/^\//, '') == this.pathname
						.replace(/^\//, '')
						&& location.hostname == this.hostname) {
					var target = jQuery('.content-box,.skipuser');
					target = target.length ? target : jQuery('[name='
							+ this.hash.slice(1) + ']');
					if (target.length) {
						jQuery('html,body').animate({
							scrollTop : target.offset().top - 135
						}, 300);
						return false;
					}

					var target1 = jQuery('#front_content');
					target = target1.length ? target : jQuery('[name='
							+ this.hash.slice(1) + ']');
					if (target1.length) {
						jQuery('html,body').animate({
							scrollTop : target1.offset().top - 123
						}, 300);
						return false;
					}

				}
			});
});

jQuery(function() {
	switch (window.location.pathname) {
	case '/user/mypage':
		jQuery('.my_mission_link').addClass('active')
		jQuery('.my_activity a').removeClass('my_activity_link')
		break;
	case '/node/add/mass-pledge':
		jQuery('.mass_pledge_link').addClass('active')
		jQuery('.my_activity a').removeClass('my_activity_link')
		break;
	case '/swachh-bhawan-map':
		jQuery("#block-system-main-menu ul li a").each(function() {
			var swachhbhawan = jQuery(this).attr('id');
			if (swachhbhawan === 'swachhbhawan_menu') {
				jQuery(this).addClass("active");
			}
		});
		break;
	case '/view-activity-map':
		jQuery("#block-system-main-menu ul li a").each(function() {
			var swachhbhawan = jQuery(this).attr('id');
			if (swachhbhawan === 'sb-act') {
				jQuery(this).addClass("active");
			}
		});
		break;
	case '/challenge-by-location':
		jQuery("#block-system-main-menu ul li a").each(function() {
			var swachhbhawan = jQuery(this).attr('id');
			if (swachhbhawan === 'sb_chall') {
				jQuery(this).addClass("active");
			}
		});
		break;
	case '/sbm-activity-map':
		jQuery("#block-system-main-menu ul li a").each(function() {
			var swachhbhawan = jQuery(this).attr('id');
			if (swachhbhawan === 'sbm-pakhwada') {
				jQuery(this).addClass("active");
			}
		});
		break;

	}
});

jQuery(function() {
	jQuery('#text_resize_decrease').attr("title", "Decrease Text");
	jQuery('#text_resize_reset').attr("title", "Reset Text ");
	jQuery('#text_resize_increase').attr("title", "Increase Text");
	jQuery('#edit_search').attr('placeholder', 'Search By Title');

	jQuery(window).scroll(function() {
		if (jQuery(window).scrollTop() > 0) {
			jQuery('body').addClass('fixed');

		} else {
			jQuery('body').removeClass('fixed');
		}
	})
	jQuery(window).resize(function() {
		if (jQuery(window).width() > 980) {
			jQuery('#main-menu .menu').css('display', 'block');
		} else {
			jQuery('#main-menu .menu').css('display', 'none');
		}
	});

	jQuery('.swachh_pledge').click(
			function() {
				jQuery(this).toggleClass('open');
				jQuery('.open_pledge').slideToggle();
				jQuery('.swachh_pledge').attr('title',
						'Click here to view message');
				jQuery('.open.swachh_pledge').attr('title',
						'Click here to close message');
			})

	jQuery('.pledge_mid_content .segregate_pledge ').click(
			function() {
				jQuery(this).toggleClass('open');
				jQuery('.blue_green_pledge').slideToggle();
				jQuery('.pledge_mid_content .segregate_pledge').attr('title',
						'Click here to view message');
				jQuery('.pledge_mid_content .open.segregate_pledge').attr(
						'title', 'Click here to close message');
			})

	jQuery('.get_vote_result').on('click', function() {
		jQuery(this).toggleClass('show-result');
		jQuery(this).next('.total_month_result').slideToggle();
	})
});

(function($) {
	Drupal.behaviors.my_activities_challenges = {
		attach : function(context, settings) {
			jQuery('.view-my-activities-and-challenges')
					.once(
							"view-id-my_activities_and_challenges",
							function() {

								jQuery(
										'.view-my-activities-and-challenges .notpublished')
										.parents('.views-row').addClass(
												'unpublish-state');
								jQuery(
										'.view-my-activities-and-challenges .published')
										.parents('.views-row').addClass(
												'publish-state');

							});
		}
	}
}(jQuery));

/* changed on 14 sep 2016 */

(function($) {

	Drupal.behaviors.image_holder = {
		attach : function(context, settings) {
			var sliderSets = jQuery('.image-holder');

			jQuery(sliderSets)
					.each(
							function() {
								var targetSlider = "#"
										+ jQuery(this).children('.bxslider')
												.attr('id');
								var targetPager = "#"
										+ jQuery(this).children('.bx-pager')
												.attr('id');
								var bslider = jQuery(targetSlider).bxSlider({
									video : true,
									controls : false,
									pagerCustom : targetPager,
									infiniteLoop : false,
									hideControlOnEnd : true,
									adaptiveHeight : true,
									useCSS : false
								});

								/**
								 * Display type changer. GRID and LIST view of
								 * page blocks. *
								 */
								jQuery('#display_type_changer a')
										.on(
												'click',
												function() {
													jQuery(
															'#display_type_changer a')
															.removeClass(
																	'active');
													jQuery(this).addClass(
															'active');
													var className = jQuery(this)
															.attr('rel');
													jQuery('#block-system-main')
															.removeClass();
													jQuery('#block-system-main')
															.addClass(className)
															.addClass(
																	'block block-system');
													bslider.reloadSlider();
												})
							});

		}
	}

}(jQuery));

/** Add title on every link. * */

/** Add title on every link. * */

(function($) {

	Drupal.behaviors.my_activities = {

		attach : function(context, settings) {
			$( ".image_thumb" ).each(function() {
			var cap = $(this).find('span').text().length;
			if(cap > 0){
				$(this).find('span').parent('.image_thumb').addClass('with_text')
				}
			})

			$('.view-challenges-new .head-title h2 a').attr('title',
					'Click here to view challenge detail ');

			$('.view-swachhbharat-activities .head-title h2 a').attr('title',
					'Click here to view activity detail');

			$('.view-swachhbharat-activities a.username').attr('title',
					'Click here to view User challenge');

			$('.view-user-activities .views-row .head-title a.username').attr(
					'title', 'Click here to view User record');

			$(
					'.volunteer-image a img, .view-challenges-new .profile-iamge img,.node-challenge .profile-iamge img, .view-user-activity-challenges .profile-iamge img')
					.attr('title', 'Click here to view user timeline');

			$(
					'.node-challenge h3 a.username, .view-challenges-new h3 a.username, .view-user-activity-challenges h3 a.username')
					.attr('title', 'Click here to view user timeline');

			$('.image-holder li.image_thumb a').attr('title',
					'Click here to view image in large scale.');

			$('.activities_left .node_user_name a.username').attr('title',
					'Click here to view user timeline');

			$('.activities_left .node_user_pic .user-picture img').attr(
					'title', 'Click here to view user timeline');

			$('.view-user-activity-challenges .head-title h2 a').attr('title',
					'Click here to view user record');

			$(
					'.views-field-picture a, .views-field-name a, .profile-pic-challenge .user-picture a img')
					.attr('title', 'Click here to view user timeline');

		}
		
		 
		

	}

}(jQuery));

(function(jQuery) {
	Drupal.behaviors.block_views_sulb = {
		attach : function(context, settings) {
			jQuery('.ulbhead h2,.rlbhead h2')
					.click(
							function(event) {
								jQuery(this).toggleClass('expend');
								// for ulb tab page
								if (jQuery('.current_state_ulb.active_state').length > 0) {
									jQuery('.current_state_ulb').slideToggle();

								} else {
									jQuery(this).parents('.state-ulb').find(
											'#block-views-sulb-block').stop(
											true, true).slideToggle();
								}
								
								// for rlb tab page								
								if (jQuery('.current_state_rlb.active_state').length > 0) {
									jQuery('.current_state_rlb').slideToggle();

								} else {
									jQuery(this).parents('.state-rlb').find(
											'#block-views-sulb-block-1').stop(
											true, true).slideToggle();
								}
							})

			jQuery('.close_state').click(function(event) {
				// for ulb tab page
				jQuery('.current_state_ulb').slideUp();
				jQuery('.open_current_ulb').hide();
				jQuery('#block-views-sulb-block').show();
				jQuery('#block-views-sulb-block').addClass("active_state");
				jQuery('.current_state_ulb').removeClass("active_state");
				
				
				// for rlb tab page
				jQuery('.current_state_rlb').slideUp();
				jQuery('.open_current_rlb').hide();
				jQuery('#block-views-sulb-block-1').show();
				jQuery('#block-views-sulb-block-1').addClass("active_state");
				jQuery('.current_state_rlb').removeClass("active_state");

			});

			jQuery('.map-instruction').click(function(event) {
				jQuery(this).hide();
			});
			jQuery('.no_ulb_found').delay(5000).fadeOut('fast');
			jQuery('.no_rlb_found').delay(5000).fadeOut('fast');

			var url = window.location;
			
			//for live
			if (url == "https://swachhbharat.mygov.in/sb-ulb") {
				if (jQuery.cookie('msg') == null) {
					jQuery('.map-instruction').show();
					jQuery.cookie('msg', 'str');
				} else {
					jQuery(".map-instruction").css('display', 'none');
				}
			}
			//for live
			if (url == "https://swachhbharat.mygov.in/sb-rlb") {
				if (jQuery.cookie('msg') == null) {
					jQuery('.map-instruction').show();
					jQuery.cookie('msg', 'str');
				} else {
					jQuery(".map-instruction").css('display', 'none');
				}
			}
			
			//for staging
			if (url == "https://swachhbharat-staging.mygov.in/sb-ulb") {
				if (jQuery.cookie('msg') == null) {
					jQuery('.map-instruction').show();
					jQuery.cookie('msg', 'str');
				} else {
					jQuery(".map-instruction").css('display', 'none');
				}
			}
			
			// for staging
			if (url == "https://swachhbharat-staging.mygov.in/sb-rlb") {
				if (jQuery.cookie('msg1') == null) {
					jQuery('.map-instruction').show();
					jQuery.cookie('msg1', 'str');
				} else {
					jQuery(".map-instruction").css('display', 'none');
				}
			}


		}

	}
})(jQuery);// JavaScript Document

(function($) {

	Drupal.behaviors.my_activities_challenges = {

		attach : function(context, settings) {

			jQuery('.view-user-activity-challenges')
					.once(
							"view-activities-and-challenges",
							function() {

								jQuery(
										'.view-user-activity-challenges .tab-inner-left')
										.parents('.views-row').addClass(
												'challenge-view');

							});

		}

	}

}(jQuery));

/** Compaign Filter( SB Activity Page ). * */
(function($) {
	Drupal.behaviors.compaign_filter = {
		attach : function(context, settings) {
			$('#compaign_select').change(function() {
				var sec_val = jQuery('#compaign_select option:selected').val();
				if (sec_val == 'All') {
					jQuery("#edit-field-compaign-list-target-id").val("");
					jQuery(".views-submit-button input").click();
				} else {
					jQuery("#edit-field-compaign-list-target-id").val(sec_val);
					jQuery(".views-submit-button input").click();
				}
			});
			$('.filter_value input').change(function() {
				if ($(this).attr('checked') !== undefined) {
					if ($(this).val() == 'all') {
						$("#edit-challenge").change().val("All");
						$(".views-submit-button input").click();
					}
					if ($(this).val() == 'activity') {
						$("#edit-challenge").change().val("no");
						$(".views-submit-button input").click();
					}
					if ($(this).val() == 'challenge') {
						$("#edit-challenge").change().val("yes");
						$(".views-submit-button input").click();
					}
				}
			});
			$('#edit_search_btn').click(function(e) {
				var text_search = jQuery('#edit_search').val();
				$('#edit-title').val(text_search);
				$(".views-submit-button input").click();
			});
			$('#edit_search').keyup(function(e) {
				if (e.keyCode == 13) {
					var text_search = jQuery('#edit_search').val();
					$('#edit-title').val(text_search);
					$(".views-submit-button input").click();
				}
			});
		}
	}
}(jQuery));

/** SBM MINISTRY FILTER( SBM PAGE). * */
(function($) {
	Drupal.behaviors.sbm_ministry_filter = {
		attach : function(context, settings) {
			$('#sbm_ministry').change(function() {
				var sec_val = jQuery('#sbm_ministry option:selected').text();
				if (sec_val == 'Select Ministry') {
					jQuery("#edit-ministry").val("");
					jQuery(".views-submit-button input").click();
				} else {
					jQuery("#edit-ministry").val(sec_val);
					jQuery(".views-submit-button input").click();
				}
			});
			$('#sbm_pakhwada').change(
					function() {
						var pakhwada_text = jQuery(
								'#sbm_pakhwada option:selected').text();
						var pakhwada_val = jQuery(
								'#sbm_pakhwada option:selected').val();
						if (pakhwada_val == 'All') {
							jQuery("#edit-activity-period").val("");
							jQuery(".views-submit-button input").click();
						} else {
							jQuery("#edit-activity-period").val(pakhwada_text);
							jQuery(".views-submit-button input").click();
						}
					});
			$('#edit_search_btn').click(function(e) {
				var text_search = jQuery('#edit_search').val();
				$('#edit-title').val(text_search);
				$(".views-submit-button input").click();
			});
			$('#edit_search').keyup(function(e) {
				if (e.keyCode == 13) {
					var text_search = jQuery('#edit_search').val();
					$('#edit-title').val(text_search);
					$(".views-submit-button input").click();
				}
			});
		}
	}
}(jQuery));

// reset button SBM Ministry on SBM page
function removevalue_ministry() {
	jQuery(".sbm_ministry_filter :text").val("");
	jQuery(
			"#views-exposed-form-sbm-xml-data-page #edit-title,#views-exposed-form-sbm-xml-data-page #edit-activity-period,#views-exposed-form-sbm-xml-data-page-1 #edit-activity-period")
			.val("");
	jQuery(".sbm_ministry_filter #sbm_ministry").val('All').trigger('change');
	jQuery(".sbm_ministry_filter #sbm_pakhwada").val('All').trigger('change');
}

jQuery(document).ready(function() {
	jQuery('#bxslider').bxSlider({
		auto : true,
		mode : 'fade',
		pager : false,
		controls : false,
		easing : 'swing',
		autoHover : true
	});
});
