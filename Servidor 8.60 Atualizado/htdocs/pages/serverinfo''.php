<?php
if(!defined('INITIALIZED'))
	exit;
$main_content .= '
<div class="SmallBox">
	<div class="MessageContainer">
		<div class="BoxFrameHorizontal" style="background-image:url(./layouts/tibiarl/images/content/box-frame-horizontal.gif);"></div>
		<div class="BoxFrameEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></div>
		<div class="BoxFrameEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></div>
		<div class="Message">
			<div class="BoxFrameVerticalLeft" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></div>
			<div class="BoxFrameVerticalRight" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></div>
			<table style="width:100%;">
				<tbody>
					<tr>
						<td style="width:100%;text-align:center;">
							<nobr>[<a href="#rates">Rates</a>]</nobr>
							<nobr>[<a href="#info">Info Server</a>]</nobr>
							<nobr>[<a href="#stamina">Stamina Infos</a>]</nobr>
							<nobr>[<a href="#house">House</a>]</nobr>
							<nobr>[<a href="#frags">Frags</a>]</nobr>
							<nobr>[<a href="#donations">Donate Info</a>]</nobr>
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="BoxFrameHorizontal" style="background-image:url(./layouts/tibiarl/images/content/box-frame-horizontal.gif);"></div>
		<div class="BoxFrameEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></div>
		<div class="BoxFrameEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></div>
	</div>
</div><br>';

$main_content .= '
<a name="rates"></a>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<div class="Text">Rates</div>
			<span class="CaptionVerticalRight" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			</div>
		</div>
		<table class="Table3" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
					<div class="InnerTableContainer">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td>
										<div class="TableShadowContainerRightTop">
											<div class="TableShadowRightTop" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rt.gif);"></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rm.gif);">
											<div class="TableContentContainer">
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#505050">
														<td><font class="white">Name</font></td><td><font class="white">Value</font></td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td width="50%"><center><font size="5"><b>Tabela de Experiência</b></font><br><b>(Stages)</b></center></td>
														<td><table width="100%"><tr align="center" bgcolor="#505050"><td class="white">From Level</td><td class="white">To Level</td><td class="white">Rate</td></tr>
														';
															if($config['server']['experienceStages'])
																{
																	$stages = new DOMDocument();
																	if($stages->load($config['site']['serverPath'] . 'data/XML/stages.xml'))
																	{
																		$stagesOfFirstWorldInStages = $stages->getElementsByTagName('world')->item(0);
																		$worldMultiplier = $stagesOfFirstWorldInStages->getAttribute('multiplier');
																		foreach($stagesOfFirstWorldInStages->getElementsByTagName('stage') as $stage)
																		{
																			$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
																			$main_content .= '<tr align="center" bgcolor="'.$bgcolor.'"><td>'. $stage->getAttribute('minlevel') . '</td>' ;
																			if($stage->hasAttribute('maxlevel'))
																			{
																				$main_content .= '<td>' . $stage->getAttribute('maxlevel') . '</td>';
																			}
																			else
																			{
																				$main_content .= '<td><img src="/images/infinity.png"></td>';
																			}
																			$main_content .= '<td>' . ($stage->getAttribute('multiplier') * $worldMultiplier) . 'x</td></tr>';
																		}
																	}
																	else
																	{
																		$main_content .= 'Cannot load experience stages.';
																	}
																}
																else
																{
																	$main_content .= $config['server']['rateExperience'] . 'x';
																}																		
														$main_content .= '
														</table></td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Skill</td><td>15x</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Magic</td><td>5x</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Loot</td><td><b>(Custom)</b></td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Spawn</td><td>1.5~2x</td>
													</tr>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer">
											<div class="TableBottomShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bm.gif);">
											<div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bl.gif);"></div>
											<div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-br.gif);"></div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div><br>';

$main_content .= '
<a name="info"></a>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<div class="Text">Info Server</div>
			<span class="CaptionVerticalRight" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			</div>
		</div>
		<table class="Table3" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<div class="InnerTableContainer">
							<table style="width:100%;">
								<tbody>
									<tr>
										<td>
											<div class="TableShadowContainerRightTop">
												<div class="TableShadowRightTop" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rt.gif);"></div>
											</div>
											<div class="TableContentAndRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rm.gif);">
											<div class="TableContentContainer">';
												$housesfree = $SQL->query('SELECT COUNT(1) FROM `houses` WHERE `owner`=0;')->fetch();
												$banned = $SQL->query('SELECT COUNT(1) FROM `bans` WHERE `id`>0;')->fetch();
												$accounts = $SQL->query('SELECT COUNT(1) FROM `accounts` WHERE `id`>0;')->fetch();
												$players = $SQL->query('SELECT COUNT(1) FROM `players` WHERE `id`>0;')->fetch();
												$guilds = $SQL->query('SELECT COUNT(1) FROM `guilds` WHERE `id`>0;')->fetch();
												$main_content .='
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#505050">
														<td width="50%"><font class="white">Name</font></td><td><font class="white">Value</font></td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>World Type</td><td>Open-PvP</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Client Version</td><td>8.60</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Free Houses</td> <td>'.$housesfree[0].'</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Banned accounts:</td> <td>'.$banned[0].'</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Accounts in database:</td> <td>'.$accounts[0].'</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Players in database:</td> <td>'.$players[0].'</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Guilds in databese:</td><td>'.$guilds[0].'</td>
													</tr>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer">
											<div class="TableBottomShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bm.gif);">
											<div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bl.gif);"></div>
											<div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-br.gif);"></div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div><br>';

$main_content .= '
<a name="stamina"></a>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<div class="Text">Stamina Infos</div>
			<span class="CaptionVerticalRight" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
		</div>
	</div>
	<table class="Table3" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>
					<div class="InnerTableContainer">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td>
										<div class="TableShadowContainerRightTop">
											<div class="TableShadowRightTop" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rt.gif);"></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rm.gif);">
											<div class="TableContentContainer">
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#505050">
														<td width="50%"><font class="white">Time</font></td><td><font class="white">Infomation</font></td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>42:00 ~ 40:00</td><td>Bonus +50% of EXP/Per Stages.</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>39:59 ~ 14:00</td><td>EXP Simple/Per Stages.</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>13:59 ~ 00:00</td><td>Reduction -50% of EXP/Per Stages.</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>08:00 ~ 00:00</td><td>Not Drop Loot.</td>
													</tr>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer">
											<div class="TableBottomShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bm.gif);">
											<div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bl.gif);"></div>
											<div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-br.gif);"></div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div><br>';

$main_content .= '
<a name="house"></a>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<div class="Text">House Infos</div>
			<span class="CaptionVerticalRight" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
		</div>
	</div>
	<table class="Table3" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>
					<div class="InnerTableContainer">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td>
										<div class="TableShadowContainerRightTop">
											<div class="TableShadowRightTop" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rt.gif);"></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rm.gif);">
											<div class="TableContentContainer">
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#D4C0A1">
														<td><center><i>O aluguel da casa será cobrado semanalmente.</i></center></td>
													</tr>
												</table>
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#F1E0C6">
														<td><center><i>O valor do aluguel será o mesmo que você pagou ao comprar a casa.</i></center></td>
													</tr>
												</table>
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#D4C0A1">
														<td><center><i>Você pode pagar o aluguel deixando o dinheiro no banco ou depot.</i></center></td>
													</tr>
												</table>
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#F1E0C6">
														<td><center>Para mais informações, <a href="?subtopic=houses">clique aqui</a>.</center></td>
													</tr>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer">
											<div class="TableBottomShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bm.gif);">
											<div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bl.gif);"></div>
											<div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-br.gif);"></div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div><br>';

$main_content .= '
<a name="frags"></a>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<div class="Text">Frags</div>
			<span class="CaptionVerticalRight" style="background-image:url(./layouts/tibiarl/images/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(./layouts/tibiarl/images/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/content/box-frame-edge.gif);"></span>
		</div>
	</div>
	<table class="Table3" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>
					<div class="InnerTableContainer">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td>
										<div class="TableShadowContainerRightTop">
											<div class="TableShadowRightTop" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rt.gif);"></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-rm.gif);">
											<div class="TableContentContainer">
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#505050">
														<td width="50%"><font class="white">Name</font></td><td><font class="white">Value</font></td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>White Skull Time</td><td>1 minutes</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Red Skull Time</td><td>24 hours</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Black Skull Time</td><td>48 hours</td>
													</tr>
													<tr bgcolor="#F1E0C6">
														<td>Frags to Red Skull <img src="\layouts\tibiarl\images\redskull.gif"/></td><td><b>Daily:</b> 150 <b>Weekly:</b> 400 <b>Monthly:</b> 1000</td>
													</tr>
													<tr bgcolor="#D4C0A1">
														<td>Frags to Black Skull <img src="\layouts\tibiarl\images\blackskull.gif"/></td><td><b>Daily:</b> 400 <b>Weekly:</b> 1500 <b>Monthly:</b> 9000</td>
													</tr>
												</table>
												<table border="0" cellpadding="4" cellspacing="1" width="100%">
													<tr bgcolor="#F1E0C6">
														<td><center>Para mais informações, utilize o comando: <b>!frags</b></center></td>
													</tr>
												</table>
											</div>
										</div>
										<div class="TableShadowContainer">
											<div class="TableBottomShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bm.gif);">
											<div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-bl.gif);"></div>
											<div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiarl/images/content/table-shadow-br.gif);"></div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div><br>';



$main_content .= '
<a name="donations"></a>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer"> 
			<span class="CaptionEdgeLeftTop" style="background-image:url(./layouts/tibiarl/images/global/content/box-frame-edge.gif);"></span> 
			<span class="CaptionEdgeRightTop" style="background-image:url(./layouts/tibiarl/images/global/content/box-frame-edge.gif);"></span> 
			<span class="CaptionBorderTop" style="background-image:url(./layouts/tibiarl/images/global/content/table-headline-border.gif);"></span> 
			<span class="CaptionVerticalLeft" style="background-image:url(./layouts/tibiarl/images/global/content/box-frame-vertical.gif);"></span>
			<div class="Text">Sistema de doa&ccedil;&otilde;es e shop online</div>
			<span class="CaptionVerticalRight" style="background-image:url(./layouts/tibiarl/images/global/content/box-frame-vertical.gif);"></span> 
			<span class="CaptionBorderBottom" style="background-image:url(./layouts/tibiarl/images/global/content/table-headline-border.gif);"></span> 
			<span class="CaptionEdgeLeftBottom" style="background-image:url(./layouts/tibiarl/images/global/content/box-frame-edge.gif);"></span> 
			<span class="CaptionEdgeRightBottom" style="background-image:url(./layouts/tibiarl/images/global/content/box-frame-edge.gif);"></span> 
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
										<div class="TableShadowRightTop" style="background-image:url(./layouts/tibiarl/images/global/content/table-shadow-rt.gif);" ></div>
									</div>
									<div class="TableContentAndRightShadow" style="background-image:url(./layouts/tibiarl/images/global/content/table-shadow-rm.gif);" >
										<div class="TableContentContainer" >
											<table class="TableContent" width="100%">
												<tr style="background-color:#D4C0A1;" >
													<td>
														<strong>Entenda como usar nosso sistema de doa&ccedil;&otilde;es e o shop online.</strong>
														<p>Nosso shop online não é P2W. Voc&ecirc; tem praticidade em suas doa&ccedil;&otilde;es e compra de itens no servidor, e tudo autom&aacute;tico. Pensando em tornar sua jornada em nosso servidor um pouco mais atrativa, os melhores sets não são vendidos no SHOP. Esse set só poderá ser obtido através de WorldBosses e missões in-game. Sua jornada em nosso servidor será totalmente balanceada e assim, incentivando sua ajuda para que o servidor venha a crescer.<br><br><b>Note que:</b> Todos os itens, exceto <i>Vip Boots e Vip helmet</i>, podem ser obtidos dentro do jogo.</p><p>Clique nos links e veja como é fácil fazer uma doação e como funciona o <a href="?subtopic=accountmanagement">Sistema de Doa&ccedil;&otilde;es</a> e o <a href="?subtopic=shopsystem">Shop Online</a>.</p>
													</td>
													<td width="30%"><img src="/images/info.jpg"></td>
												</tr>
											</table>
										</div>
									</div>
									<div class="TableShadowContainer" >
										<div class="TableBottomShadow" style="background-image:url(./layouts/tibiarl/images/global/content/table-shadow-bm.gif);" >
											<div class="TableBottomLeftShadow" style="background-image:url(./layouts/tibiarl/images/global/content/table-shadow-bl.gif);" ></div>
											<div class="TableBottomRightShadow" style="background-image:url(./layouts/tibiarl/images/global/content/table-shadow-br.gif);" ></div>
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
?>