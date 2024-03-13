<?php
/**
 * @package     ${NAMESPACE}
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use function YOOtheme\app;
use YOOtheme\Config;
use YOOtheme\View;

use Joomla\Input\Input;

list($config, $view, $input) = app(Config::class, View::class, Input::class);

$rooms = $displayData;
$columns=3;
$counter=0;

?>
	<div class="uk-container">
		<div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-grid-margin">
			<div class="uk-width-1-1" >
				<div class="uk-margin">
					<ul class="uk-grid uk-child-width-1-1 uk-child-width-1-<?=$columns;?>@m uk-grid-match" uk-grid="">

					<?php
					foreach ($rooms as $room):
						$counter++;?>
						<li data-id="<?= $room->id ?>" class="room roomlistRoom room-<?= $room->id ?> <?php if($counter % $columns == 0): echo 'uk-first-column';endif;?>">
							<div class="el-item uk-panel uk-margin-remove-first-child">
								<?php
								$images = json_decode($room->images, true);
								if(!empty($images))
								{
									$firstImage = '/' . $images['images0']['image'];
									$image = $view->el('image', [

										'class' => [
											'el-image',
										],
										'src' => $firstImage,
										'alt' => 'Foto von ' . $room->title,
										'width' => null,
										'height' => null,
										'uk-img' => true,
										//'uk-cover' => true,
										'thumbnail' => true,
									]);

									echo $image();
								}
								?>
								<h3 class="el-title uk-margin-top uk-margin-remove-bottom"> <?= $room->title ?> </h3>
								<div class="el-meta uk-text-meta uk-margin-top">meta</div>
								<div class="el-content uk-panel uk-margin-top"><p><?= $room->description ?></p></div>
							</div>
						</li>
					<?php
					endforeach;
					?>

					</ul>
				</div>


			</div>
		</div>
	</div>
