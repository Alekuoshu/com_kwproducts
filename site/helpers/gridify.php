<?php
/**
 * @copyright   Copyright (C) 2013 jnilla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see http://www.gnu.org/licenses/gpl-2.0.html
 * @credits     Check credits.html included in this package or repository for a full list of credits
 */


defined('JPATH_BASE') or die;

/**
 * Class GridifyHelper
 *
 * @since  1.6
 */
class GridifyHelper {

	public static function setGrid($cols, $items)
	{
		// init
		if($cols < 1 || $cols > 12) throw new Exception('invalid parameter');
		$items = $items["items"]; if(!$cols) throw new Exception('invalid parameter');
		$n = 0;
		$span = "span".round(12/$cols);
		foreach ($items as $item) {
			$n++;
			if($n == 1) {
				echo '<div class="row-fluid">';
			}
			echo '<div class='.$span.'>'.$item.'</div>';
			if($n == $cols) {
				echo '</div>';
				$n = 0;
			}
		}
		if($n > 0) echo "</div>";
	}

}
