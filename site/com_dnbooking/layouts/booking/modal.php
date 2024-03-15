<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;
use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\View;

use Joomla\Input\Input;

list($config, $view, $input) = app(Config::class, View::class, Input::class);

$data = $displayData;

?>
<p><h3>Hallo <?= $data['firstname'] . ' ' . $data['lastname']?>, Ihre Buchung im Überblick</h3></p>

