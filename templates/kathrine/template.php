<?php
defined('MYAAC') or die('Direct access not allowed!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo template_place_holder('head_start'); ?>
		<link rel="stylesheet" href="<?php echo $template_path; ?>/style.css" type="text/css" />
		<script type="text/javascript">
			<?php
				echo $twig->render('menu.js.html.twig', array('categories' => $config['menu_categories']));
			?>
		</script>
		<script type="text/javascript" src="tools/basic.js"></script>
		<script type="text/javascript">
			<?php
			$menus = get_template_menus();
			
			function get_template_pages($category) {
				global $menus;
				
				$ret = array();
				foreach($menus[$category] as $menu) {
					$ret[] = $menu['link'];
				}
				
				return $ret;
			}
			?>
			var category = '<?php
					if(strpos(URI, 'subtopic=') !== false) {
						$tmp = array($_REQUEST['subtopic']);
					}
					else {
						$tmp = explode('/', URI);
					}
					
				if(in_array($tmp[0], get_template_pages(MENU_CATEGORY_NEWS)))
					echo 'news';
				elseif(in_array($tmp[0], get_template_pages(MENU_CATEGORY_LIBRARY)))
						echo 'library';
				elseif(in_array($tmp[0], get_template_pages(MENU_CATEGORY_COMMUNITY)))
						echo 'community';
				elseif(in_array($tmp[0], array_merge(get_template_pages(MENU_CATEGORY_ACCOUNT), array('account'))))
					echo 'account';
				elseif(in_array($tmp[0], get_template_pages(MENU_CATEGORY_SHOP)))
					echo 'shops';
				?>';
		</script>
		<?php echo template_place_holder('head_end'); ?>
	</head>

	<body onload="initMenu();">
		<?php echo template_place_holder('body_start'); ?>
		<div id="top"></div>
		<div id="page">
		<!-- Keep all on center of browser -->

			<!-- Header Section -->
			<div id="header"></div>
			<!-- End -->

			<!-- Menu Section -->
			<div id="tabs">
				<?php
				foreach($config['menu_categories'] as $id => $cat) {
					if($id != MENU_CATEGORY_SHOP || $config['gifts_system']) { ?>
				<span id="<?php echo $cat['id']; ?>" onclick="menuSwitch('<?php echo $cat['id']; ?>');"><?php echo $cat['name']; ?></span>
				<?php
					}
				}
				?>
			</div>

			<div id="mainsubmenu">
				<?php
				foreach($menus as $category => $menu) {
					echo '<div id="' . $config['menu_categories'][$category]['id'] . '-submenu">';
					if(!isset($menus[$category])) {
						return;
					}
					
					$size = count($menus[$category]);
					$i = 0;
					
					foreach($menus[$category] as $menu) {
						if(strpos(trim($menu['link']), 'http') === 0) {
							echo '<a href="' . $menu['link'] . '" target="_blank">' . $menu['name'] . '</a>';
						}
						else {
							echo '<a href="' . getLink($menu['link']) . '">' . $menu['name'] . '</a>';
						}
						
						if(++$i != $size) {
							echo '<span class="separator"></span>';
						}
					}
					
					echo '</div>';
				}
				?>
			</div>
			<!-- End -->

			<!-- Content Section -->
			<div id="content">
				<div id="margins">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td><a href="<?php echo getLink('news'); ?>"><?php echo $config['lua']['serverName']; ?></a> &raquo; <?php echo $title; ?></td>
							<td>
							<?php
							if($status['online'])
								echo '
								<font color="green"><b>Server Online</b></font> &raquo;
								Players Online: ' . $status['players'] . ' / ' . $status['playersMax'] . ' &raquo;
								Monsters: ' . $status['monsters'] . ' &raquo; Uptime: ' . (isset($status['uptimeReadable']) ? $status['uptimeReadable'] : 'Unknown') . '';
							else
								echo '<font color="red"><b>Server Offline</b></font>';
							?>
							</td>
						</tr>
					</table>
					<hr noshade="noshade" size="1" />
					<div class="Content"><div id="ContentHelper">
					<?php echo tickers() . template_place_holder('center_top') . $content; ?>
					</div></div>
				</div>
			</div>
			<div id="content-bot"></div>
			<div id="copyrights">
				<p><?php echo template_footer(); ?></p>
<?php
	if($config['template_allow_change'])
		 echo '<font color="white">Template:</font><br/>' . template_form();
 ?>
			</div>
			<!-- End -->

		<!-- End -->
		</div>
		<?php echo template_place_holder('body_end'); ?>
	</body>
</html>
