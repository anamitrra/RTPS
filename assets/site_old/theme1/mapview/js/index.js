/******map******/
//var map;

//require(["esri/map", "dojo/domReady!"], function (Map) {
//    map = new Map("map", {
//        basemap: "hybrid",  //For full list of pre-defined basemaps, navigate to http://arcg.is/1JVo6Wd
//        center: [-122.45, 37.75], // longitude, latitude
//        zoom: 4
//    });
//});
/******map******/
var maps;

//require(["esri/map", "dojo/domReady!"], function (Map) {
//    maps = new Map("map2", {
//        basemap: "hybrid",  //For full list of pre-defined basemaps, navigate to http://arcg.is/1JVo6Wd
//        center: [77, 30], // longitude, latitude
//        zoom: 5
//    });
//});



/*****to close the sales graph section******/

    $(document).ready(function () {
        $("#hide").click(function () {
            $("#hidden").hide();
        });
    });


/*****to close the sales graph section******/

/*******for bar chart*******/

    $(document).ready(function () {
    var bar = $('.chart__list__item'),
          pts = "points",
          thisPts,
          maxVal = Math.max(
        bar.eq(0).data(pts),
        bar.eq(1).data(pts),
        bar.eq(2).data(pts),
        bar.eq(3).data(pts),
        bar.eq(4).data(pts),
        bar.eq(5).data(pts),
        bar.eq(6).data(pts),
        bar.eq(7).data(pts),
        bar.eq(8).data(pts),
        bar.eq(9).data(pts));
    for (i = 0; i <= bar.length; i++) {
        thisPts = bar.eq(i).data(pts) / maxVal * 80;
        bar.eq(i).css('height', thisPts + '%');
    }
});



/*******for bar chart*******/

/**hover menu drop down****/


    ; (function ($, window, undefined) {
        // outside the scope of the jQuery plugin to
        // keep track of all dropdowns
        var $allDropdowns = $();

        // if instantlyCloseOthers is true, then it will instantly
        // shut other nav items when a new one is hovered over
        $.fn.dropdownHover = function (options) {
            // don't do anything if touch is supported
            // (plugin causes some issues on mobile)
            // bram outcomment
            if ('ontouchstart' in document) return this; // don't want to affect chaining

            // the element we really care about
            // is the dropdown-toggle's parent
            $allDropdowns = $allDropdowns.add(this.parent());

            return this.each(function () {
                var $this = $(this),
                    $parent = $this.parent(),
                    defaults = {
                        delay: 500,
                        instantlyCloseOthers: true
                    },
                    data = {
                        delay: $(this).data('delay'),
                        instantlyCloseOthers: $(this).data('close-others')
                    },
                    showEvent = 'show.bs.dropdown',
                    hideEvent = 'hide.bs.dropdown',
                    // shownEvent  = 'shown.bs.dropdown',
                    // hiddenEvent = 'hidden.bs.dropdown',
                    settings = $.extend(true, {}, defaults, options, data),
                    timeout;

                $parent.hover(function (event) {
                    // so a neighbor can't open the dropdown
                    if (!$parent.hasClass('open') && !$this.is(event.target)) {
                        // stop this event, stop executing any code
                        // in this callback but continue to propagate
                        return true;
                    }

                    openDropdown(event);
                }, function () {
                    timeout = window.setTimeout(function () {
                        $parent.removeClass('open');
                        $this.trigger(hideEvent);
                    }, settings.delay);
                });

                // this helps with button groups!
                $this.hover(function (event) {
                    // this helps prevent a double event from firing.
                    // see https://github.com/CWSpear/bootstrap-hover-dropdown/issues/55
                    if (!$parent.hasClass('open') && !$parent.is(event.target)) {
                        // stop this event, stop executing any code
                        // in this callback but continue to propagate
                        return true;
                    }

                    openDropdown(event);
                });

                // handle submenus
                $parent.find('.dropdown-submenu').each(function () {
                    var $this = $(this);
                    var subTimeout;
                    $this.hover(function () {
                        window.clearTimeout(subTimeout);
                        $this.children('.dropdown-menu').show();
                        // always close submenu siblings instantly
                        $this.siblings().children('.dropdown-menu').hide();
                    }, function () {
                        var $submenu = $this.children('.dropdown-menu');
                        subTimeout = window.setTimeout(function () {
                            $submenu.hide();
                        }, settings.delay);
                    });
                });

                function openDropdown(event) {
                    $allDropdowns.find(':focus').blur();

                    if (settings.instantlyCloseOthers === true)
                        $allDropdowns.removeClass('open');

                    window.clearTimeout(timeout);
                    $parent.addClass('open');
                    $this.trigger(showEvent);
                }
            });
        };

        $(document).ready(function () {
            // apply dropdownHover to all elements with the data-hover="dropdown" attribute
            $('[data-hover="dropdown"]').dropdownHover();


        });
    })(jQuery, this);
    /* End Bootstrap Hover Dropdown jQuery plugin by Cameron Spear
     *
     *  Begin custom slidedown by Atelierbram **/
    $(document).ready(function () {

        var itms = {
            home: ($("#tt-home li").length),
            blog: ($("#tt-blog li").length),
            books: ($("#tt-books li").length),
            goodies: ($("#tt-goodies li").length),
            media: ($("#tt-media li").length),
            news: ($("#tt-news li").length),
            contact: ($("#tt-contact li").length)
        };

        // add a class on the parent element with the appropriate value for the number of list-items
        jQuery.each(itms, function (i, val) {
            $("#tt-" + i).addClass('tt-nav__itemcount-' + val);
        });

        // make the top toggle-links clickable, [read more about this here](https://github.com/atelierbram/bootstrap-hover-dropdown)
        // effectively make create two dropdowns: this is for touch
        $('.dropdown').on({

            'hide.bs.dropdown': function (e) {

                $(this).on('click', function (e) {
                    var self = $(this);
                    $(this).toggleClass('is-clicked').siblings('.dropdown').removeClass('is-clicked');

                    if ('ontouchstart' in document && self.hasClass('open', 'is-clicked')) {
                        e.stopPropagation();
                    }
                    else {
                        return this;
                    }
                });
            }

        });

        // effectively make create two dropdowns: this is for desktop - or to be precise: non-touch
        $('.dropdown').on({

            'show.bs.dropdown': function (e) {

                $(this).on('click', function (e) {

                    if ('ontouchstart' in document) {
                        return this;
                    }
                    else {
                        e.stopPropagation();
                    }
                });
            }

        });

    });

    document.getElementById("dashboard").onclick = function () {
        location.href = "index.html";
    };




