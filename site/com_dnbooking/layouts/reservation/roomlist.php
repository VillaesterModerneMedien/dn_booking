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

$form = $displayData;
$columns=3;
$counter=0;

?>
	<div class="uk-container">
		<div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-grid-margin">
			<div class="uk-width-1-1" >
				<div class="uk-margin">
					<?php echo $form->renderField('room_id'); ?>
                </div>
			</div>
		</div>
	</div>
