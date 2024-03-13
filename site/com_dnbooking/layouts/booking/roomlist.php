<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

$rooms = $displayData;

foreach ($rooms as $room) {
	echo "<h1>" . $room->title . "</h1><br/>";
}