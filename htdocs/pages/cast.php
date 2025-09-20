<?php
if (!defined('INITIALIZED'))
	exit;
$getCasts = $SQL->query("SELECT * FROM `live_casts` ORDER BY `spectators` DESC")->fetchAll();
$main_content .= "
	<script>
function requestFlashPermission() {
	var iframe = document.createElement('iframe');
	iframe.src = 'https://get.adobe.com/flashplayer';
	iframe.sandbox = '';
	document.body.appendChild(iframe);
	document.body.removeChild(iframe);
}

var isNewEdge = (navigator.userAgent.match(/Edge\/(\d+)/) || [])[1] > 14;
var isNewSafari = (navigator.userAgent.match(/OS X (\d+)/) || [])[1] > 9;
var isNewChrome = (navigator.userAgent.match(/Chrom(e|ium)\/(\d+)/) || [])[2] > 56
	&& !/Mobile/i.test(navigator.userAgent);
var canRequestPermission = isNewEdge || isNewSafari || isNewChrome;

if (!swfobject.hasFlashPlayerVersion('10') && canRequestPermission) {
	requestFlashPermission();
	// Chrome requires user's click in order to allow iframe embeding
	$(window).one('click', requestFlashPermission);
}
</script>
";

$main_content .= '

	<p>Atualmente nosso servidor possui um sistema de Cast exclusivo.</p>
	<p>Você pode assistir nossos jogadores apenas clicando para entrar na conta sem entrar na conta, muito menos a senha. Uma lista dos jogadores que estão transmitindo será exibida, então escolha um e clique para entrar. Após entrar no elenco do jogador, você pode interagir com ele através do Spectator Chat, um chat onde você pode conversar com o jogador e outros espectadores.</p>
	<p>Se você é um jogador e deseja transmitir seu jogo, digite seu personagem e use o comando <strong>!Cast</strong>, Se você quiser colocar uma senha em seu elenco digite o comando <strong>!Cast password</strong>, na palavra <strong>password</strong> voce coloca a senha requirida.</p>
	<p><strong>OBS: o cast precisa ta off para usar comando !cast "suasenha"</strong>.</p>
	<div class="TableContainer">
		<div class="CaptionContainer">
			<div class="CaptionInnerContainer">
				<span class="CaptionEdgeLeftTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
				<span class="CaptionEdgeRightTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
				<span class="CaptionBorderTop" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);"></span>
				<span class="CaptionVerticalLeft" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);"></span>
				<div class="Text">Information</div>
				<span class="CaptionVerticalRight" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);"></span>
				<span class="CaptionBorderBottom" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);"></span>
				<span class="CaptionEdgeLeftBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
				<span class="CaptionEdgeRightBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
			</div>
		</div>
		<table class="Table3" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<div class="InnerTableContainer" >
							<table style="width:100%;" >
								<tr>
									<td>
										<div class="TableShadowContainerRightTop" >
											<div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
											<div class="TableContentContainer" >
												<table class="TableContent" width="100%">
													<tr bgcolor="' . $config['site']['darkborder'] . '">
														<td class="LabelV" >Status</td>
														<td style="width:90%;" >' . (($config['status']['serverStatus_online'] == 1) ? 'Online' : 'Offline') . '</td>
													</tr>
													<tr bgcolor="' . $config['site']['lightborder'] . '">
														<td class="LabelV" >Players in Cast</td>
														<td style="width:90%;" >' . count($getCasts) . '</td>
													</tr>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer" >
											<div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
												<div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
												<div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	</br>
';

$main_content .= '
	<div class="TableContainer">
		<div class="CaptionContainer">
			<div class="CaptionInnerContainer">
				<span class="CaptionEdgeLeftTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
				<span class="CaptionEdgeRightTop" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
				<span class="CaptionBorderTop" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);"></span>
				<span class="CaptionVerticalLeft" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);"></span>
				<div class="Text">Players in Cast</div>
				<span class="CaptionVerticalRight" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-vertical.gif);"></span>
				<span class="CaptionBorderBottom" style="background-image:url(' . $layout_name . '/images/global/content/table-headline-border.gif);"></span>
				<span class="CaptionEdgeLeftBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
				<span class="CaptionEdgeRightBottom" style="background-image:url(' . $layout_name . '/images/global/content/box-frame-edge.gif);"></span>
			</div>
		</div>
		<table class="Table3" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<div class="InnerTableContainer" >
							<table style="width:100%;" >
								<tr>
									<td>
										<div class="TableShadowContainerRightTop" >
											<div class="TableShadowRightTop" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rt.gif);" ></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-rm.gif);" >
											<div class="TableContentContainer" >
												<table class="TableContent" width="100%">
													<tr bgcolor="' . $config['site']['darkborder'] . '">
														<td class="LabelV"  width="5%">Outfit</td>
														<td class="LabelV" width="70%">Name</td>
														<td class="LabelV" width="10%">Version</td>
														<td class="LabelV" width="5%">Spectators</td>
														<td class="LabelV" width="5%">Password</td>
														<td class="LabelV" width="5%">Watch</td>
													</tr>';
$cast_number = 0;
if (count($getCasts) > 0)
	foreach ($getCasts as $cast) {

		$player = new Player();
		$player->loadById($cast['player_id']);

		$bgcolor = (($cast_number++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
		$cur_outfit .= "<img style='text-decoration:none;margin: 0 0 0 -13px;' class='outfitImgsell2' src='" . $player->makeOutfitUrl() . "' alt='' name=''>";
		$main_content .= '
													<tr bgcolor="' . $bgcolor . '">
														<td width="32px" style="position:relative;">' . $cur_outfit . '

														</td>
														<td><a href="?subtopic=characters&name=' . urlencode($player->getName()) . '">' . htmlspecialchars($player->getName()) . '</a><br><small>(' . htmlspecialchars($vocation_name[$player->getVocation()]) . ' - Level ' . $player->getLevel() . ')</small></td>
														<td width="32px" align="center">' . (($cast['version'] >= 1120) ? '<img src="images/tibia11.png" alt="version ' . $cast['version'] . '" name="' . $cast['version'] . '">' : '<img src="images/tibia10.png" name="' . $cast['version'] . '" alt="' . $cast['version'] . '">') . '</td>
														<td width="32px" align="center">' . $cast['spectators'] . '</td>
														<td width="32px" align="center">' . (($cast['password'] == 1) ? '<img src="images/lockcast.png" width="32px" alt="Cast Locked">' : '<img src="images/unlock.png" width="32px" alt="Cast Unlocked">') . '</td>
														<td width="32px" align="center">
															<span id="PlayButtonOf_' . $player_number_counter . '" style="display: ' . $display . ';">
															<span name="FlashClientPlayButton" id="FlashClientPlayButton" >';
		if ($cast['password'] == 1) {
			$main_content .= '<img style="border:0px;" src="' . $layout_name . '/images/account/play-button-disabled.gif">';
		} else if (!$config['site']['flash_client_enabled']) {
			$main_content .= '<img style="border:0px;" alt="Flash client disabled" src="' . $layout_name . '/images/account/play-button-disabled.gif">';
		} else {
			if ($cast['version'] >= 1120)
				$main_content .= '<a href="?subtopic=play11&cast=1&name=' . htmlspecialchars($player->getName()) . '" target="_blank">';
			else if ($cast['version'] < 1110) {
				$main_content .= '<a href="?subtopic=play&cast=1&name=' . htmlspecialchars($player->getName()) . '" target="_blank">';
			}

			$main_content .= '
																		<img style="border:0px;" src="' . $layout_name . '/images/account/play-button.gif"></a>';
		}

		$main_content .= '
																</span>
															</span>

														</td>
													</tr>';
	} else {
	$main_content .= '
													<tr>
														<td colspan="6" bgcolor="' . $config['site']['lightborder'] . '">No cast at the moment.</td>
													</tr>';
}
$main_content .= '
												</table>
											</div>
										</div>
										<div class="TableShadowContainer" >
											<div class="TableBottomShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bm.gif);" >
												<div class="TableBottomLeftShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-bl.gif);" ></div>
												<div class="TableBottomRightShadow" style="background-image:url(' . $layout_name . '/images/global/content/table-shadow-br.gif);" ></div>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>';
