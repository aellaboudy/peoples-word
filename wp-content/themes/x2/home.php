<?php
global $cap;

switch ($cap->homepage_style) {
	case 'magazine': 
			echo get_template_part('home','magazine');
		break;
	default:
			echo get_template_part('index');
    	break;
}

?>
