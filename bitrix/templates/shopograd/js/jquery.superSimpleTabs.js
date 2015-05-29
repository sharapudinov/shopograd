/***
@title:
Super Simple Tabs

@version:
1.4

@author:
Andreas Lagerkvist

@date:
2009-09-17
2012-02-29

@url:
http://andreaslagerkvist.com/jquery/super-simple-tabs/

@license:
http://creativecommons.org/licenses/by/3.0/

@copyright:
2008 Andreas Lagerkvist (andreaslagerkvist.com)

@requires:
jquery

@does:
This is an extremely basic tabs-plugin which allows you to create tabbed content from the ever-so-common list of in-page-links. You can set the selected tab, the show and hide animation as well as the duration through the config argument.

@howto:
jQuery('ul.tabs').superSimpleTabs(); would make every ul with the class 'tabs' hide show the content its links are pointing to.

@exampleHTML:
<ul>
	<li><a href="#jquery-super-simple-tabs-example-1">Content 1</a></li>
	<li><a href="#jquery-super-simple-tabs-example-2">Content 2</a></li>
	<li><a href="#jquery-super-simple-tabs-example-3">Content 3</a></li>
</ul>
<div id="jquery-super-simple-tabs-example-1">
	Content 1
</div>
<div id="jquery-super-simple-tabs-example-2">
	Content 2
</div>
<div id="jquery-super-simple-tabs-example-3">
	Content 3
</div>

@exampleJS:
jQuery('#jquery-super-simple-tabs-example ul').superSimpleTabs();
***/
jQuery.fn.superSimpleTabs = function (conf) {
	var config = jQuery.extend({
		selected:	1, 				// Which tab is initially selected (hash overrides this)
		show:		'show', 		// Show animation
		hide:		'hide', 		// Hide animation
		duration:	0,				// Animation duration
		callback:	function () {}	// Callback after tab has been clicked
	}, conf);

	return this.each(function () {
		var ul	= jQuery(this);
		var ipl	= 'a[href^="#"]';

		// Go through all the in-page links in the ul
		// and hide all but the selected's contents
		ul.find(ipl).each(function (i) {
			var link = jQuery(this);

			if ((i + 1) === config.selected) {
				link.addClass('selected');
			}
			else {
				jQuery(link.attr('href')).hide();
			}
		});

		// When clicking the UL (or anything within)
		ul.click(function (e) {
			var clicked	= jQuery(e.target);
			var link	= false;

			if (clicked.is(ipl)) {
				link = clicked;
			}
			else {
				var parent = clicked.parents(ipl);

				if (parent.length) {
					link = parent;
				}
			}

			// Only continue if the clicked element was an in page link
			if (link) {
				var selected = ul.find('a.selected');

				if (selected.length) {
					// Remove currently .selected, hide the element it was pointing to
					jQuery(selected.removeClass('selected').attr('href'))[config.hide](config.duration, function () {
						// Then show the element the clicked link was pointing to
						jQuery(link.attr('href'))[config.show](config.duration, function () {
							config.callback(link.attr('href'));
						});
					});

					link.addClass('selected');
				}
				else {
					jQuery(link.addClass('selected').attr('href'))[config.show](config.duration, function () {
						config.callback(link.attr('href'));
					});
				}

				// Update the hash
				superSimpleTabsUpdateHash(link.attr('href'));

				return false;
			}
		});

		// If a hash is set, click that tab
		var hash = window.location.hash;

		if (hash) {
			// We can't simply .click() the link since that will run the show/hide animation
			jQuery(ul.find('a').removeClass('selected').attr('href')).hide();
			jQuery(ul.find('a[href="' + hash + '"]').addClass('selected').attr('href')).show();
			config.callback(hash);
		}
	});
};

// http://stackoverflow.com/questions/1489624/modifying-document-location-hash-without-page-scrolling#answer-1489802
function superSimpleTabsUpdateHash (hash) {
	hash = hash.replace( /^#/, '' );
	var fx, node = $( '#' + hash );
	if ( node.length ) {
	  fx = $( '<div></div>' )
		      .css({
		          position:'fixed',
		          left:0,
		          top:0,
		          visibility:'hidden'
		      })
		      .attr( 'id', hash )
		      .appendTo( document.body );
	  node.attr( 'id', '' );
	}
	document.location.hash = hash;
	if ( node.length ) {
	  fx.remove();
	  node.attr( 'id', hash );
	}
}
