<?php
//
// CheezCap - Cheezburger Custom Administration Panel
// (c) 2008 - 2010 Cheezburger Network (Pet Holdings, Inc.)
// LOL: http://cheezburger.com
// Source: http://code.google.com/p/cheezcap/
// Authors: Kyall Barrows, Toby McKes, Stefan Rusek, Scott Porad
// License: GNU General Public License, version 2 (GPL), http://www.gnu.org/licenses/gpl-2.0.html
//

$themename = 'Theme'; // used on the title of the custom admin page
$req_cap_to_edit = 'read'; // the user capability that is required to access the CheezCap settings page

function cap_get_options() {
	$pages     = get_pages();
	$option    = Array();
	$option[0] = "All pages";
	$i         = 1;
	foreach ($pages as $pagg) {
		$option[$i] = $pagg->post_title;
		$i++;
	}
	$option_pages = $option;

	$x2_fonts_options = get_option('tk_google_fonts_options');

	$x2_fonts = array(
		'Ubuntu',
		'Arial, sans-serif',
		'Helvetica, sans-serif',
		'Century Gothic, Avant Garde, sans-serif',
		'Arial Black, sans-serif',
		'Impact, sans-serif',
		'Times New Roman, Times, serif',
		'Garamond, serif'
	);
	if(defined('TK_GOOGLE_FONTS')){
		if(isset($x2_fonts_options['selected_fonts'])){
			foreach ($x2_fonts_options['selected_fonts'] as $key => $x2google_font) {
				if($x2google_font != 'Ubuntu')
				 array_push($x2_fonts, $x2google_font = str_replace("+", " ", $x2google_font));
			}
		}
	}

	$tk_loop_designer_options	= get_option('tk_loop_designer_options');
	$entry_style = Array();
	$entry_style[] = "bubble";
	$entry_style[] = "default";
	$entry_style[] = "image_caption";

	if(defined('TK_LOOP_DESIGNER')){
		if(isset($tk_loop_designer_options['template_options']) && is_array($tk_loop_designer_options['template_options'])){
	    	foreach ($tk_loop_designer_options['template_options'] as $tempalte_key => $loop_designer_option) {
	    		$entry_style[] = $tempalte_key;
			}
		}
	}

	$x2_buddypress = new Group (__("BuddyPress",'x2'), "buddypress",
		array(
		new DropdownOption(
            __("Show groups header","x2"),
            __("Display group header, can be used as widget area.","x2"),
			"bp_groups_header",
			array('on', 'off'),
			'on',
			'start',
			'Groups'),
		new DropdownOption(
            __("Groups header style","x2"),
            __("How much info do you want in your groups header? <br>
			slim = just the stuff that's really needed. <br>
			full = all the info you might need. ","x2"),
			"bp_groups_header_style",
			array('slim', 'full'),
			'slim',
			false),
		new DropdownOption(
            __("Groups sidebars","x2"),
            __("Where do you like to have your sidebars in groups? <br>
			default = the global settings and sidebars will be used<br>
			none = no sidebars, full width<br>
			left = left group sidebar, this will overwrite the global settings and display the left group sidebar<br>
			right = right group sidebar, this will overwrite the global settings and display the right group sidebar<br>
			left and right = this option will display the left and right group sidebars and overwrite the global settings<br>
			Note: all sidebars can be filled with widgets. Without widgets there will be the group avatar and information like in the group header.","x2"),
			"bp_groups_sidebars",
			 array('default', 'none', 'left', 'right', 'left and right'),
			 'default',
			 false),
		new TextOption(
            __("Groups avatar size","x2"),
            __("Define the size of the group avatar. Width and height is the same. <br>
			Just write a number, without px.","x2"),
			"bp_groups_avatar_size",
			"",
			'',
			false),
		new TextOption(
            __("Groups menu order","x2"),
            __("Change the menu order in the groups. Write the order in by slug, comma-separated. <br>
			Note: a slug is the name as it is written in the url, <br>
			means all letters in small, no symbols, ...","x2"),
			"bp_groups_nav_order",
			"",
			'',
			'end'),
		new DropdownOption(
            __("Show profile header","x2"),
            __("Display profile header, can be used as widget area.","x2"),
			"bp_profile_header",
			array('on', 'off'),
			'on',
			'start',
			'Profiles'),
		new DropdownOption(
            __("Profile header style","x2"),
            __("How much info do you want in your profile header? <br>
			slim = just the stuff that's really needed. <br>
			full = all the info you might need. ","x2"),
			"bp_profile_header_style",
			array('slim', 'full'),
			'slim',
			false),
		new DropdownOption(
            __("Profile sidebars","x2"),
            __("Where do you like to have your sidebars in profiles? <br>
			default = the global settings and sidebars will be used<br>
			none = no sidebars, full width<br>
			left = left profile sidebar, this will overwrite the global settings and display the left profile sidebar<br>
			right = right profile sidebar, this will overwrite the global settings and display the right profile sidebar<br>
			left and right = This option will display the left and right profile sidebars and overwrite the global settings<br>
			Note: all sidebars can be filled with widgets. Without widgets there will be the user avatar and information like in the member header.","x2"),
			"bp_profile_sidebars",
			array('default', 'none', 'left', 'right', 'left and right'),
			'default',
			false),
		new TextOption(
            __("Profile avatar size","x2"),
            __("Define the size of the profile avatar. Width and height is the same.","x2"),
			"bp_profiles_avatar_size",
			"",
			'',
			false),
		new TextOption(
            __("Profile menu order","x2"),
            __("Change the menu order in the profiles. Write the order in by slug, comma-separated. <br>
			Note: a slug is the name as it is written in the url, <br>
			means all letters in small, no symbols, ...","x2"),
			"bp_profiles_nav_order",
			"",
			'',
			'end'),
		new BooleanOption(
            __("Use BuddyPress sub-navigation","x2"),
            __("This sub-navigation is the secondary level navigation, <br>
			e.g. for profile it contains: [Public, Edit Profile, Change Avatar]<br>
			If you use the community navigation widget, you don't need this navigation. <br>
			If you want to use a horizontally sub-navigation - choose this one.","x2"),
			"bp_default_navigation",
			true,
			'start',
			'BuddyPress sub-navigation'),
		new ColorOption(
            __("BuddyPress sub-navigation background colour","x2"),
            __("Change the background colour of the BuddyPress component sub navigation.","x2"),
			"bg_content_nav_color",
			"",
			'end'),
		new BooleanOption(
            __("Show search bar","x2"),
            __("Enable BuddyPress search bar in header.","x2"),
			"menu_enable_search",
			true,
			'start',
			'BuddyPress search'),
		new BooleanOption(
            __("Use global Buddydev search instead of bp-search","x2"),
            __("Replace the BuddyPress search (which comes with dropdown menu) with the Buddydev search. <br>
			The Buddydev search is an easy one-field global search with nice result-listing.","x2"),
			"buddydev_search",
			true,
			false),
		new DropdownOption(
            __("Search bar horizontal position","x2"),
            __("If selected, you want the search bar left or right?","x2"),
			"searchbar_x",
			array('right', 'left'),
			'right',
			false),
		new TextOption(
            __("Search bar vertical position","x2"),
            __("Distance from search bar to top (in px), just enter a number.","x2"),
			"searchbar_y",
			"",
			'',
			'end'),
		new DropdownOption(
            __("Show login widget in sidebar","x2"),
            __("Turn auto BuddyPress login in the right sidebar on/off. <br>
			Note: You can also add the login as a widget into every widgetarea you like.","x2"),
			"login_sidebar",
			array('on', 'off'),
			'on',
			'start',
			'BuddyPress login'),
		new TextOption(
            __("Login widget sidebar text","x2"),
            __("When logged out: what text should be displayed in the login sidebar?","x2"),
			"bp_login_sidebar_text",
			"",
			'',
			'end'),
		)
		);

 $groups = array(
	new Group (__("General",'x2'), "general",
		array(
		new DropdownOption(
            __("Colour scheme","x2"),
            __("Select a colour scheme for your website. <br>
			A color scheme is a set of 8 colors. Choose a color scheme as starting point. <br />
			Later you can change every single color - if you want to. <a href='http://support.themekraft.com/entries/22150567-color-scheme'>More.</a>","x2"),
			"style_css",
			apply_filters('x2_get_color_scheme', array( 'white', 'light', 'dark')),
			'white'),

		new DropdownOption(
            __("Sidebar position","x2"),
            __("Where do you like to have your sidebars? Define your default layout. <br>
			You can also use other sidebar layouts in your pages or for the groups or member profiles. <br>
			You can customize your sidebars in the sidebar tab. ","x2"),
			"sidebar_position",
			array('right', 'left and right', 'left', 'full-width'),
			'right'),
		new BooleanOption(
            __("Use standard WordPress background settings","x2"),
            __("Enable this option, if you like to use the standard wordpress settings page.","x2"),
			"wp_custom_background",
			false,
			'start',
			'Background'),
			new ColorOption(
                __("Background colour","x2"),
                __("Change your background colour","x2"),
				"bg_body_color",
				"",
				'',
				''),
			new FileOption(
                __("Background image","x2"),
                __("Insert your own background image. Upload or insert url.","x2"),
				"bg_body_img",
				'',
				false,
				''),
			new BooleanOption(
                __("Fixed background image","x2"),
                __("Fix the position of your body background image","x2"),
				"bg_body_img_fixed",
				false,
				false,
				''),
			new DropdownOption(
                __("Background position","x2"),
                __("Position of the background image: center, left, right","x2"),
				"bg_body_img_pos",
				array('center', 'left', 'right'),
				'',
				false,
				''),
		new DropdownOption(
            __("Background repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_body_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'',
			'end',
			''),
			)
	),
	new Group (__("Home",'x2'), "home",
		array(
		// Homepage style
		new DropdownOption(
            __("Homepage style","x2"),
            __("<b>Widget homepage or display your latest posts?</b><br>
			<br>
			default = show my latest posts<br>
			magazine = widgetized homepage<br>
			<br>
			* To make this option work, you also need to go to <br>
			<i>Settings -> Reading and set your frontpage to 'your latest posts'.</i><br>
			<br>
			<b>Want a static frontpage AND show your latest posts on another page?</b><br>
			For example you want a homepage and another page \"blog\", which is not your frontpage then, but your blog homepage.<br>
			Then leave this option here as default, edit your static frontpage and select the \"magazine homepage\" as page template to use. <br> ","x2"),
			"homepage_style",
			array('default', 'magazine'),
			"default"),
		// Widgetized Homepage
		new TextOption(
            __("Widgetarea height","x2"),
            __("Set the height of the 3 sidebar columns so they have a fixed height when filled. <br>
			In px, Just enter a number. Per default the height is set to auto. <br>
			Note: Don't fix if you use elements with a dynamic height <br>
			(like the accordion) - then better use the options below..","x2"),
			"widget_homepage_height",
			"",
			"",
			"start",
			"Widget homepage"),
		new DropdownOption(
            __("Homepage widgetarea style","x2"),
            __("Choose a style for the homepage widgetareas. Default is 'boxes'.","x2"),
			"widget_homepage_widgetarea_style",
			array('boxes', 'simple'),
			"boxes",
			'end'),
		)
	),
	new Group (__("Blog",'x2'), "blog",
		array(
		// archive, tag and category views - (blog post listing)
		new DropdownOption(
            __("Post listing style","x2"),
            __('Select a style to display the latest posts in the archive-, tag- and category-views of your blog.
			<span style="color: #888;" class="get_loopd"><br><br><i>Loop-Designer-Ready!</i>
			Get more loop templates available here, which you can easily customize or create your ownes. Get full control of how your post listings look with the
			<a href="http://themekraft.com/store/customize-wordpress-loop-with-tk-loop-designer/" target="_blank">TK Loop Designer Plugin</a>.</span>',"x2"),
			"posts_lists_style",
			$entry_style,
			"bubble",
			'start',
			'Archive view'),
		new DropdownOption(
            __("Show / hide avatars","x2"),
            __("Show or hide the avatars in the post listing. <br>
			This option is for the archive-, tag- and category-views of your blog.","x2"),
			"posts_lists_hide_avatar",
			array('show', 'hide'),
			"show",
			"",
			false),
		new DropdownOption(
            __("Show / hide date, category and author","x2"),
            __("Show or hide the date, category and author in the blog post listings.","x2"),
			"posts_lists_hide_date",
			array('show', 'hide'),
			"show",
			false),
		new DropdownOption(
            __("Show / hide page navigation","x2"),
            __("Show or hide the page navigation onder the post listing.","x2"),
			"posts_lists_hide_pagenavi",
			array('show', 'hide', 'pagenavi'),
			"show",
			false),
		new TextOption(
            __("Pagetitle for category views","x2"),
            __("Write your own pagetitle for the category views. The category name will be appended automatically. <br>
			By default the title is 'You are browsing the blog for CATEGORY-NAME.'<br>
			Write something fresh! ;)","x2"),
			"category_pagetitle",
			"",
			"",
			'end'),
		// Single post view options
		new DropdownOption(
            __("Show / hide avatar","x2"),
            __("Show or hide the avatar in the single post view.","x2"),
			"single_post_hide_avatar",
			array('show', 'hide'),
			"show",
			"start",
			"Single post view"),
		new DropdownOption(
            __("Show / hide date and category","x2"),
            __("Show or hide the date and category in the single post view.","x2"),
			"single_post_hide_date",
			array('show', 'hide'),
			"show",
			false),
		new DropdownOption(
            __("Show / hide tags","x2"),
            __("Show or hide the tags in the single post view.","x2"),
			"single_post_hide_tags",
			array('show', 'hide'),
			"show",
			false),
		new DropdownOption(
            __("Show / hide comment info","x2"),
            __("Show or hide the comment info in the single post view.","x2"),
			"single_post_hide_comment_info",
			array('show', 'hide'),
			"show",
			'end'),
		// excerpt options
		new DropdownOption(
            __("Show excerpts","x2"),
            __("Use excerpts or show full content of your posts in category and archive views. ","x2"),
			"excerpt_on",
			array('content', 'excerpt'),
			"content",
			"start",
			"Excerpts"),
		new TextOption(
            __("Excerpt length","x2"),
            __("Change the excerpt length, default is 30 words.","x2"),
			"excerpt_length",
			"","","end"),
		)
	),
	new Group (__("Header",'x2'), "header",
		array(
		new BooleanOption(
            __("Standard WordPress header","x2"),
            __("Enable this option, if you like to use the standard wordpress custom image header settings page.","x2"),
			"add_custom_image_header",
			false),
		new DropdownOption(
            __("Show header title text","x2"),
            __("Show header title text? You can disable the text and just upload an image. See below.","x2"),
			"header_text",
			array('on', 'off'),
			'on',
			'start',
			'Header title'),
		new ColorOption(
            __("Header title text colour","x2"),
            __("Change header font colour","x2"),
			"header_text_color",
			"",
			"",
			""),
		new FileOption(
            __("Header title logo","x2"),
            __("Insert your own logo. It will be linked to your homepage and aligned left from default. Upload or insert url.","x2"),
			"logo",
			"",
			false,
			""),
		new DropdownOption(
            __("Header title position","x2"),
            __("Choose if you want to display the header title before or after the widgetareas. <br>
			You can also drop the logo in a header widgetarea in the left, center, right or full-width. Default = after widgets.","x2"),
			"header_title_position",
			array("before widgets", "full-width", "left", "center", "right", "after widgets"),
			"after widgets",
			"end"),
		new DropdownOption(
            __("Header width","x2"),
            __("Do you like the header in full width or as wide as your site?","x2"),
			"header_width",
			array('default', 'full-width'),
			'default'),
		new TextOption(
            __("Header height","x2"),
            __("Your header height in px. Just enter a number. Leave blank = auto. <br>
			* If you use the bottom menu, you might need to adjust its position in \"Menu Bottom\" -> \"Vertical position\". <br>
			* This is not your header image height, you can add your header background image in the fields below.<br>
			* If you want to use a certain height for a background image, you'll need to fiddle with this option a bit.","x2"),
			"header_height",
			""),
		new ColorOption(
            __("Header background colour","x2"),
            __("Change header background colour.","x2"),
			"header_bg_color",
			"",
			'start',
			'Header background'),
		new FileOption(
            __("Header image","x2"),
            __("Insert your own header image. Upload or insert url. <br>
			Default width is 1000px, the height (and full width option) can be adjusted above. <br>
			For no image write 'none'.","x2"),
			"header_img",
			'',
			false),
		new DropdownOption(
            __("Header image repeat","x2"),
            __("Repeat header image: x=horizontally, y=vertically","x2"),
			"header_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			"no repeat",
			false),
		new DropdownOption(
            __("Header image x-position","x2"),
            __("If header image is smaller, you can choose to align left, center or right.","x2"),
			"header_img_x",
			array('left', 'center', 'right'),
			"left",
			false),
		new TextOption(
            __("Header image y-position","x2"),
            __("Distance from header image to top (in px), just enter a number.","x2"),
			"header_img_y",
			"",
			"",
			"end"),
		)
		),
	new Group (__("Menu Top",'x2'), "menu-top",
		array(
		new BooleanOption(
            __("Stay on top when scrolling","x2"),
            __("Keep the top menu on top of the display when scrolling down?<br>
            The menu will always be fullwidth then, too.","x2"),
            "menu_top_stay_on_top",
            true),
		new DropdownOption(
            __("Horizontal position","x2"),
            __("Align the menu left or right.","x2"),
			"menu_top_x",
			array('left', 'right'),
			'left'),
		new DropdownOption(
            __("Menu style","x2"),
            __("Choose a menu style","x2"),
			"bg_menu_top_style",
			array('flat style', 'tab style', 'closed style', 'bordered' ),
			'flat style'),
		new ColorOption(
            __("Menu border","x2"),
            __("Would you like to underline your menu? Select a colour.","x2"),
			"menu_top_underline",
			""),
		new ColorOption(
            __("Menu font colour","x2"),
            __("Change menu font colour.","x2"),
			"menu_top_link_color",
			"",
			'start',
			'Menu fonts'),
		new ColorOption(
            __("Menu font colour &raquo; current and mouse over","x2"),
            __("Change menu font colour from currently displayed menu item <br>
			or when mouse moves over.","x2"),
			"menu_top_link_color_current",
			"",
			'end'),
		new ColorOption(
            __("Menu background colour","x2"),
            __("Change the menu bar's general background colour.","x2"),
			"bg_menu_top_link_color",
			"",
			'start',
			'Menu background'),
		new FileOption(
            __("Menu background image","x2"),
            __("Insert your own background image for the menu bar. Upload or insert url.","x2"),
			"bg_menu_top_img",
			"",
			'',
			false),
		new DropdownOption(
            __("Menu background repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_menu_top_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'no repeat',
			'end'),
		new ColorOption(
            __("Menu background colour &raquo; current","x2"),
            __("Change background colour from currently displayed menu item.","x2"),
			"bg_menu_top_link_color_current",
			"",
			'start',
			'Menu background &raquo; current'),
		new FileOption(
            __("Menu background image &raquo; current","x2"),
            __("Background image of the currently displayed menu item. Upload or insert url.","x2"),
			"bg_menu_top_img_current",
			"",
			'',
			false),
		new DropdownOption(
            __("Menu background image repeat &raquo current","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_menu_top_img_current_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'no repeat',
			'end'),
		new ColorOption(
            __("Menu background colour &raquo; mouse over and drop down list","x2"),
            __("Change a menu item's background colour when mouse moves over it, <br>
			and drop down background colour","x2"),
			"bg_menu_top_link_color_hover",
			""),
		new ColorOption(
            __("Menu background colour &raquo; drop down list &raquo; mouse over","x2"),
            __("Change background colour of HOVERED DROP DOWN menu item <br>
			(when the mouse moves over it).","x2"),
			"bg_menu_top_link_color_dd_hover",
			""),
		new DropdownOption(
            __("Menu corner radius","x2"),
            __("Do you want your menu corners to be rounded?","x2"),
			"menu_top_corner_radius",
			array('not rounded', 'just the bottom ones', 'all rounded'),
			'not rounded'),
		)
		),
	new Group (__("Menu Bottom",'x2'), "menu-bottom",
		array(
		new BooleanOption(
            __("Stay on top when scrolling","x2"),
            __("Keep the main navigation on top of the display when scrolling down?","x2"),
            "menu_waypoints",
            true),
		new DropdownOption(
            __("Horizontal position","x2"),
            __("Align the menu left or right.","x2"),
			"menu_x",
			array('left', 'right'),
			'left'),
		new TextOption(
            __("Vertical position","x2"),
            __("If you played around with the header height option, you might need to adjust the position of this menu. <br>
			This can be done by adjusting the spacing above that menu here.<br>
			Leave blank = auto height. Default spacing = 40px.","x2"),
			"header_menu_spacing",
			""),
		new DropdownOption(
            __("Menu style","x2"),
            __("Choose a menu style","x2"),
			"bg_menu_style",
			array('flat style', 'tab style', 'closed style', 'bordered' ),
			'flat style'),
		new ColorOption(
            __("Menu border","x2"),
            __("Would you like to underline your menu? Select a colour.","x2"),
			"menu_underline",
			""),
		new ColorOption(
            __("Menu font colour","x2"),
            __("Change menu font colour.","x2"),
			"menu_link_color",
			"",
			'start',
			'Menu fonts'),
		new ColorOption(
            __("Menu font colour &raquo; current and mouse over","x2"),
            __("Change menu font colour from currently displayed menu item <br>
			or when mouse moves over.","x2"),
			"menu_link_color_current",
			"",
			'end'),
		new ColorOption(
            __("Menu background colour","x2"),
            __("Change the menu bar's general background colour.","x2"),
			"bg_menu_link_color",
			"",
			'start',
			'Menu background'),
		new FileOption(
            __("Menu background image","x2"),
            __("Insert your own background image for the menu bar. Upload or insert url.","x2"),
			"bg_menu_img",
			"",
			'',
			false),
		new DropdownOption(
            __("Menu background repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_menu_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'no repeat',
			'end'),
		new ColorOption(
            __("Menu background colour &raquo; current","x2"),
            __("Change background colour from currently displayed menu item.","x2"),
			"bg_menu_link_color_current",
			"",
			'start',
			'Menu background &raquo; current'),
		new FileOption(
            __("Menu background image &raquo; current","x2"),
            __("Background image of the currently displayed menu item. Upload or insert url.","x2"),
			"bg_menu_img_current",
			"",
			'',
			false),
		new DropdownOption(
            __("Menu background image repeat &raquo current","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_menu_img_current_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'no repeat',
			'end'),
		new ColorOption(
            __("Menu background colour &raquo; mouse over and drop down list","x2"),
            __("Change a menu item's background colour when mouse moves over it, <br>
			and drop down background colour","x2"),
			"bg_menu_link_color_hover",
			""),
		new ColorOption(
            __("Menu background colour &raquo; drop down list &raquo; mouse over","x2"),
            __("Change background colour of HOVERED DROP DOWN menu item <br>
			(when the mouse moves over it).","x2"),
			"bg_menu_link_color_dd_hover",
			""),
		new DropdownOption(
            __("Menu corner radius","x2"),
            __("Do you want your menu corners to be rounded?","x2"),
			"menu_corner_radius",
			array('not rounded', 'just the bottom ones', 'all rounded'),
			'not rounded'),
		)
		),
	new Group (__("Content",'x2'), "content",
		array(
		new ColorOption(
            __("Content colour","x2"),
            __("Change the background colour of the content. <br>
			By default it is the same as the body background color.","x2"),
			"bg_container_color",
			"",
			"start",
			"Content"),
			new ColorOption(
                __("Content Alternative Colour","x2"),
                __("Choose an alternative background colour for your content. Will be used for things as lines, widgettitles etc. ","x2"),
				"bg_container_alt_color",
				"",
				'',
				''),
			new DropdownOption(
                __("Show / hide the vertical lines","x2"),
                __("The vertical lines that divide your content are shown by default. <br>
				Here you can disable them if you like.","x2"),
				"bg_container_nolines",
				array('show', 'hide'),
				"show",
				false),
			new FileOption(
                __("Content background image","x2"),
                __("Change background image for the content. Upload or insert url.","x2"),
				"bg_container_img",
				"",
				false),
			new DropdownOption(
                __("Content background repeat","x2"),
                __("Repeat background image: x=horizontally, y=vertically","x2"),
				"bg_container_img_repeat",
				array('no repeat', 'x', 'y', 'x+y'),
				"",
				false),
			new ColorOption(
                __("Details colour","x2"),
                __("Change your details background colour","x2"),
				"bg_details_color",
				"",
				'',
				''),
			new ColorOption(
                __("Details alternative colour","x2"),
                __("Change your details alternative/hover background colour","x2"),
				"bg_details_hover_color",
				"",
				'end'),
		new DropdownOption(
            __("Title font style","x2"),
            __('Change the title font style (h1 and h2). Default is the Google Font "Ubuntu". <span style="color: #888;" class="get_loopd"><br><br>Get more Google Fonts available in your theme options - with the <a href="http://themekraft.com/store/tk-google-fonts-wordpress-plugin/" target="_blank">TK Google Fonts Plugin</a>.</span>',"x2"),
			"title_font_style",
			$x2_fonts,
			'Ubuntu',
			"start",
			"Titles"),
			new TextOption(
                __("Title size","x2"),
                __("Change the title font size (h1 and h2), default is 31px (for h2), just enter a number. <br>
				(h1 should only be used once for the site title)","x2"),
				"title_size",
				"",
				"",
				false),
			new DropdownOption(
                __("Titles font weight","x2"),
                __("Do you want your titles bold or normal?","x2"),
				"title_weight",
				array('bold', 'normal'),
				"normal",
				false),
		new ColorOption(
            __("Title colour","x2"),
            __("Change title colour.","x2"),
			"title_color",
			"","end"),
		new DropdownOption(
            __("Subtitle font style","x2"),
            __("Change the subtitle font style (h3-h6).","x2"),
			"subtitle_font_style",
			$x2_fonts,
			'Ubuntu',
			"start",
			"Subtitles"),
			new DropdownOption(
                __("Subtitles font weight","x2"),
                __("Do you want your subtitles bold or normal?","x2"),
				"subtitle_weight",
				array('bold', 'normal'),
				"normal",
				false),
		new ColorOption(
            __("Subtitle colour","x2"),
            __("Change subtitle colour","x2"),
			"subtitle_color",
			"","end"),
		new DropdownOption(
            __("Font style","x2"),
            __('Change your fonts! <span style="color: #888;" class="get_loopd"><br><br>Get more Google Fonts available in your theme options - with the <a href="http://themekraft.com/store/tk-google-fonts-wordpress-plugin/" target="_blank">TK Google Fonts Plugin</a>.</span>',"x2"),
			"font_style",
			$x2_fonts,
			'Ubuntu',
			"start",
			"Fonts"),
		new TextOption(
            __("Font size","x2"),
            __("Change the standard font size, default is 13px. Just enter a number.","x2"),
			"font_size",
			"","",
			false),
		new TextOption(
            __("Smaller font size","x2"),
            __("Change the smaller alternative font size, default is 11px. Just enter a number.","x2"),
			"font_alt_size",
			"","",
			false),
		new ColorOption(
            __("Font colour","x2"),
            __("Change font colour","x2"),
			"font_color",
			"",
			'',
			''),
		new ColorOption(
            __("Font alternative colour","x2"),
            __("Change the font alternative colour","x2"),
			"font_alt_color",
			"",
			'end'),
		new ColorOption(
            __("Link colour","x2"),
            __("Change link colour. <br>
			Notes: You just need to change the link colour to have a nice effect on all link and button colours. <br>
			The hover colour will automatically be your font colour or the default font colour. <br>
			Optional, you can also choose a hover colour or if and when to underline.","x2"),
			"link_color",
			"",
			"start",
			"Links"),
		new ColorOption(
            __("Link hover colour","x2"),
            __("Change link colour for mouse moves over.","x2"),
			"link_color_hover",
			"",
			false),
		new DropdownOption(
            __("Link underline","x2"),
            __("Choose if (and when) to underline links.","x2"),
			"link_underline",
			array('never', 'always', 'just for mouse over', 'just when normal'),
			"never",
			false),
		new DropdownOption(
            __("BuddyPress subnavigation adapting","x2"),
            __("Use link hover colour for the BuddyPress subnav also? <br>
			This is the submenu in member and group profiles. By default the subnav links adapts the link colour and link hover colour. <br>
			Sometimes the link hover colour can look ugly here and you don't want the subnav to adapt. <br>
			Then you can change the colour adapting here easily. ","x2"),
			"link_color_subnav_adapt",
			array('just the link colour', 'link colour and hover colour'),
			"link colour and hover colour",
			'end'),
		)
		),
	new Group (__("Sidebars",'x2'), "sidebars",
		array(
		new TextOption(
            __("Left sidebar width","x2"),
            __("Change the left sidebar width - in pixel. Just enter a number. ","x2"),
			"leftsidebar_width",
			"225",
			"",
			"start",
			"Left sidebar"),
			new ColorOption(
                __("Left sidebar background colour","x2"),
                __("Change background colour of the left sidebar. ","x2"),
				"bg_leftsidebar_color",
				"",
				false),
			new FileOption(
                __("Left sidebar background image","x2"),
                __("Your own background image for the left sidebar. Upload or insert url.","x2"),
				"bg_leftsidebar_img",
				"",
				false),
		new DropdownOption(
            __("Left sidebar background repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_leftsidebar_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			"no repeat",
			'end'),
		new TextOption(
            __("Right sidebar width","x2"),
            __("Change the right sidebar width - in pixel. Just enter a number. ","x2"),
			"rightsidebar_width",
			"300",
			"",
			"start",
			"Right sidebar"),
			new ColorOption(
                __("Right sidebar background colour","x2"),
                __("Change background colour of the right sidebar. ","x2"),
				"bg_rightsidebar_color",
				"",
				false),
			new FileOption(
                __("Right sidebar background image","x2"),
                __("Your own background image for the right sidebar. Upload or insert url.","x2"),
				"bg_rightsidebar_img",
				"",
				false),
		new DropdownOption(
            __("Right sidebar background repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_rightsidebar_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			"no repeat",
			'end'),
		new DropdownOption(
            __("Sidebar widget title style","x2"),
            __("Choose a style for the widget titles","x2"),
			"bg_widgettitle_style",
			array('angled', 'rounded', 'transparent'),
			'angled'),
		new DropdownOption(
            __("Sidebar widget title font style","x2"),
            __("Change the widget title's font style.","x2"),
			"widgettitle_font_style",
			$x2_fonts,
			'Ubuntu',
			"start",
			"Sidebar widget title fonts"),
			new TextOption(
                __("Widget title font size","x2"),
                __("Font size of your widget titles in px, just enter a number, default=13","x2"),
				"widgettitle_font_size",
				"16",
				"",
				false),
		new ColorOption(
            __("Sidebar widget title font colour","x2"),
            __("Change font colour of the widget titles.","x2"),
			"widgettitle_font_color",
			"",
			'end'),
		new ColorOption(
            __("Sidebar widget title background colour","x2"),
            __("Change background colour of the widget titles.","x2"),
			"bg_widgettitle_color",
			"",
			"start",
			"Sidebar widget titles background",
			false),
		new FileOption(
            __("Sidebar widget title background image","x2"),
            __("Your own background image for the widget title. Upload or insert url.","x2"),
			"bg_widgettitle_img",
			"",
			false),
		new DropdownOption(
            __("Sidebar widget title background repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_widgettitle_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			"no repeat",
			'end'),
		new DropdownOption(
            __("Capitalizing in widgets","x2"),
            __("Capitalize lists in your widgets?","x2"),
			"capitalize_widgets_li",
			array('no', 'yes'),
			"no",
			"start",
			"Capitalizing"),
		new DropdownOption(
            __("Capitalizing the widget titles","x2"),
            __("Capitalize the titles in your widgets?","x2"),
			"capitalize_widgettitles",
			array('no', 'yes'),
			"no",
			'end'),
		new BooleanOption(
            __("Turn off the flying widget effect?","x2"),
            __("Do you want the outer widget to follow you when scrolling? <br />
            The flying widget will be displayed if you add a widget AND if the screen's width is more than 1422px. <br />
            If you want the widget to be fixed instead of following, you can turn the flying effect off here.","x2"),
            "out_of_content_widget",
            false),

		)
		),
	new Group (__("Footer",'x2'), "footer",
		array(
		new DropdownOption(
            __("Footer width","x2"),
            __("Do you like the footer in full width or as wide as your site?","x2"),
			"footer_width",
			array('full-width', 'default'),
			"full-width"),
		new TextOption(
            __("Footer height","x2"),
            __("Change the footer height, in px, just enter a number. <br>
			This option is not the footer WIDGET height, you can define that one below.","x2"),
			"footerall_height",
			""),
		new ColorOption(
            __("Footer background colour","x2"),
            __("Change background colour of the footer. Write 'transparent' for no color.","x2"),
			"bg_footerall_color",
			"",
			'start',
			'Footer background'),
		new FileOption(
            __("Footer background image","x2"),
            __("Background image for the footer background. Upload or insert url.","x2"),
			"bg_footerall_img",
			"",
			'',
			false),
		new DropdownOption(
            __("Footer background image repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_footerall_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'no repeat',
			false),
		new BooleanOption(
            __("Footer border top","x2"),
            __("Show or hide the top-border for the footer? <br>
			Container alternate color will be used. Define this color in the 'Content' tab.","x2"),
			"bg_footer_border",
			true,
			'end'),
		new TextOption(
            __("Footer widget height","x2"),
            __("Change the footer widgets height, in px, just enter a number. <br>
			This option is nice to have your footer widget areas all the same height.","x2"),
			"footer_height",
			"",
			'',
			'start',
			'Footer widgets'),
		new ColorOption(
            __("Footer widget background","x2"),
            __("Change background color of the footer widgets. By default they are transparent.","x2"),
			"bg_footer_color",
			"",
			'',
			false),
		new FileOption(
            __("Footer widget background image","x2"),
            __("Background image for the footer widgets background. Upload or insert url.","x2"),
			"bg_footer_img",
			"",
			'',
			false),
		new DropdownOption(
            __("Footer widget background image repeat","x2"),
            __("Repeat background image: x=horizontally, y=vertically","x2"),
			"bg_footer_img_repeat",
			array('no repeat', 'x', 'y', 'x+y'),
			'no repeat',
			false),
		new BooleanOption(
            __("Footer widget border","x2"),
            __("Show or hide the border for footer widgets? <br>
			Container alternate color will be used. Define this color in the 'Content' tab.","x2"),
			"bg_footer_widget_border",
			false,
			'end'),
        )
    ),
    new Group (__("Slideshow",'x2'), "slideshow",
		array(
		new DropdownOption(
            __("Enable slideshow","x2"),
            __("Enable slideshow","x2"),
			"enable_x2_slideshow",
			array('home', 'off', 'all'),
			'home'),
		new CheckboxGroupOptions(
            __("Slideshow post categories","x2"),
            __("By default, the slideshow takes images, titles and text-excerpts of the last 4 posts.<br>
			You can select the category the posts should be taken from. ","x2"),
			"category_name",
			'','',
			'start'),
		new DropdownOption(
            __("Show only sticky posts","x2"),
            __("Show only sticky posts from all or a specific category","x2"),
            "slideshow_show_sticky",
            array('on', 'off'),
            'off',
            'end'),
		new DropdownOption(
            __("Slideshow style","x2"),
            __("Select a type of slideshow. Default is the flux slider.","x2"),
			"slideshow_style",
			array(__('flux slider', 'x2'), __('nivo slider', 'x2'), __('full width', 'x2'), __('default', 'x2')),
			__('flux slider', 'x2'),
			'start'),
        new DropdownOption(
            __("Effect","x2"),
            __("Select the slideshow effect. Default is random","x2"),
            "slideshow_effect",
            array( 'random','bars', 'blinds', 'blocks', 'blocks2', 'concentric', 'dissolve', 'slide', 'warp', 'zip', 'bars3d', 'blinds3d', 'cube', 'tiles3d', 'turn'),
            'random',
            'end'),
        )
    ),
	new Group (__("CSS",'x2'), "overwrite",
		array(
		new TextOption(
            __("Overwrite CSS","x2"),
            __("This is your place to overwrite existing CSS.<br>
			This way you are able to customize even the smallest CSS details. <br>
			If you know how to write, you can play around a bit!<br>
			<br>
			Here's an example how to change your body background picture:<br>
			<br>
			body {<br>
			background-image:url(url-to-your-picture);<br>
			}<br>
			<br>","x2"),
			"overwrite_css",
			"",
			true,
			false),
		)
	),

);
	if ( function_exists( 'bp_is_active' ) )
		array_push($groups, $x2_buddypress);


		$groups = apply_filters('x2_groups',$groups);

		return $groups;
}

/**
 *
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
*/

/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

     if ( ! class_exists( 'cmb_Meta_Box' ) )
         load_template( dirname(__FILE__) . '/metaboxes/init.php');

}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
*/
add_filter( 'cmb_meta_boxes', 'x2_metaboxes' );
function x2_metaboxes( array $meta_boxes ) {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_x2_page_';

    // Pull all the categories into an array
    $options_categories = array();
    $options_categories_obj = get_categories();
    $options_categories['all-categories'] = 'All categories';
    foreach ($options_categories_obj as $category) {
        $options_categories[$category->term_id] = $category->cat_name.' ('.$category->count.')';
    }

    // Pull all tags into an array
    $options_tags = array();
    $options_tags_obj = get_tags();
    foreach ( $options_tags_obj as $tag ) {
        $options_tags[$tag->term_id] = $tag->name;
    }


    // Pull all the pages into an array
    $options_pages = array();
    $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
    $options_pages[''] = __('Select a page:','x2');
    foreach ($options_pages_obj as $page) {
        $options_pages[$page->ID] = $page->post_title;
    }
    $post_types = get_post_types(array('public' => true));

    $meta_boxes[] = array(
        'id'         => 'x2_slideshow',
        'title'      => __('x2 SlideShow','x2'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        'fields'     => apply_filters('x2_add_slideshow_meta_fields',
                        array(
                            array(
                                  'name'    => __('Enable x2 slideshow','x2'),
                                  'id'      => $prefix . 'enable_slideshow',
                                  'type'    => 'select',
                                  'options' => array(
                                      array( 'name' => 'default', 'value' => 'default', ),
                                      array( 'name' => 'off', 'value' => 'off', ),
                                      array( 'name' => 'on', 'value' => 'on', ),
                                  ),
                            ),
                            array(
                                'name'    => __('Slideshow post categories','x2'),
                                'desc'    => __('The slideshow  takes images, titles and text-excerpts of the last 4 posts.
                                                  You can select the category the posts should be taken from.','x2'),
                                'id'      => $prefix . 'category_name',
                                'type'    => 'multicheck',
                                'options' => $options_categories,
                            ),
                            array(
                                'name'    => __('Slideshow style','x2'),
                                'desc'    => __('Select a type of slideshow.<span class="get_more"><br><br><br>&raquo; Get More Options with the x2 Premium Pack.</span>','x2'),
                                'id'      => $prefix . 'slideshow_style',
                                'type'    => 'select',
                                'options' => array(
                                    array( 'name' => __('default', 'x2'), 'value' => __('default', 'x2'), ),
                                    array( 'name' => __('full width', 'x2'), 'value' => __('full width', 'x2'), ),
                                    array( 'name' => __('nivo slider', 'x2'), 'value' => __('nivo slider', 'x2'), ),
                                    array( 'name' => __('flux slider', 'x2'), 'value' => __('flux slider', 'x2'), ),
                                ),
                            )
                        ), 'x2_slideshow', $prefix, $post_types)
    );

    $meta_boxes[] = array(
        'id'         => 'list_posts',
        'title'      => __('List post under this page','x2'),
        'pages'      => array( 'page', ), // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true, // Show field names on the left
        // 'show_on'    => array( 'key' => 'id', 'value' => array( 2, ), ), // Specific post IDs to display this metabox
        'fields' => apply_filters('x2_add_meta_fields',
                array(), 'list_posts', $prefix, $post_types)
    );
    return $meta_boxes;
}
function x2_add_show_list($options, $id, $prefix, $post_types){
     array_push($options, array(
                        'name'    => __('Show / hide featured posts','x2'),
                        'desc'    => __('Display your featured posts?','x2'),
                        'id'      => $prefix . 'enable_featured_posts',
                        'type'    => 'select',
                        'options' => array(
                            array( 'name' => 'show', 'value' => 'show', ),
                            array( 'name' => 'hide', 'value' => 'hide', ),
                        ),
                    ));
     return $options;
}
add_filter('x2_add_meta_fields', 'x2_add_meta_box_fields', 10, 4);

function x2_add_meta_box_fields($options, $id, $prefix, $post_types){
     array_push($options, array(
                        'name'    => __('Show / hide featured posts','x2'),
                        'desc'    => __('Display your featured posts?','x2'),
                        'id'      => $prefix . 'enable_featured_posts',
                        'type'    => 'select',
                        'options' => array(
                            array( 'name' => 'show', 'value' => 'show', ),
                            array( 'name' => 'hide', 'value' => 'hide', ),
                        ),
                    ));
     return $options;
}
add_filter('x2_add_meta_fields', 'x2_add_meta_box_fields', 10, 4);

function x2_add_per_page_fields($options, $id, $prefix, $post_types){
     array_push($options, array(
                        'name' => __('Posts per page','x2'),
                        'desc' => __('Define the amount of posts per page.','x2'),
                        'id'   => $prefix . 'featured_posts_posts_per_page',
                        'type' => 'text_small',
                    ));
     return $options;
}
add_filter('x2_add_meta_fields', 'x2_add_per_page_fields', 40, 4);

function x2_add_pagination_fields($options, $id, $prefix, $post_types){

    $tk_loop_designer_options	= get_option('tk_loop_designer_options');
    $entry_style = Array();
    $entry_style[] = array('name' => 'default', 'value' => 'default');
    $entry_style[] = array('name' => 'bubble', 'value' => 'bubble');
    $entry_style[] = array('name' => 'image_caption', 'value' => 'image_caption');
    if(isset($tk_loop_designer_options['template_options']) && is_array($tk_loop_designer_options['template_options'])){
        foreach ($tk_loop_designer_options['template_options'] as $tempalte_key => $loop_designer_option) {
            $entry_style[] = array('name' => $tempalte_key, 'value' => $tempalte_key);
        }
    }

    array_push($options, array(
                        'name'    => __('Show pagination','x2'),
                        'id'      => $prefix . 'featured_posts_show_pagination',
                        'type'    => 'select',
                        'options' => array(
                            array( 'name' => 'show', 'value' => 'show', ),
                            array( 'name' => 'hide', 'value' => 'hide', ),
                            array( 'name' => 'use wp pagenavi plugin', 'value' => 'pagenavi', ),
                        ),
                    ),
                    array(
                        'name' => __('Post template style','x2'),
                        'id'   => $prefix . 'featured_posts_style',
                        'desc' => '<span class="get_more"><br><br>&raquo;' . __('Get More Options with the x2 Premium Pack.','x2') . '</span>',
                        'type'    => 'select',
                        'options' => $entry_style,
                    ));
    return $options;
}
add_filter('x2_add_meta_fields', 'x2_add_pagination_fields', 45, 4);

?>