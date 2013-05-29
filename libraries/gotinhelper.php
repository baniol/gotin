<?php

/**
 * Gotin helper library
 *
 *
 * @license     http://www.opensource.org/licenses/mit MIT License
 * @link        http://bundles.laravel.com/bundle/gotin
 * @filesource
 * @author      Marcin Baniowski
 * @package     Gotin\Libraries
 */

class GotinHelper {

	public static function link_icon($action, $icon, $title = null, $parameters = array(), $attributes = array()) {

		$url = URL::to_action($action, $parameters);

		if (is_null($title)) $title = $url;

		$icon_tag = '<i class="icon-'.$icon.'"></i>';

		return '<a href="'.$url.'"'.HTML::attributes($attributes).' title="'.$title.'">'.$icon_tag.'</a>';

	}

}
