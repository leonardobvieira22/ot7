<?PHP
$castle = $SQL->query('SELECT id, guild_id, guild_name, player_name, date FROM castle_web WHERE active = 1 ORDER BY id DESC;');
foreach($castle as $result) {
}
$main_content .= '<div class="BoxContent" style="background-image:url(<?php echo $layout_name; ?>/images/content/scroll.gif);">
                                                                <div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-vertical.gif);"></span>
			<div class="Text">Castle War 24H</div>
			<span class="CaptionVerticalRight" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
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
															<div class="TableShadowRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rt.gif);"></div>
														</div>
													<div class="TableContentAndRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rm.gif);">
													<div class="TableContentContainer">
<center>
</center>
<center>
</center><table border="0" cellspacing="1" cellpadding="4" width="100%"><tbody><tr bgcolor="#505050"><td class="white"><center><b>Conquistado por</b></center></td><td class="white"><center><b>Guild Name</b></center></td><td class="white"><center><b>Date and Time</b></center></td><tr bgcolor="#D4C0A1">
<td width="35%">
<center>
<a href="?subtopic=characters&amp;name='.$result['player_name'].'">'.$result['player_name'].'</a>
</center>
</td>
<td width="30%">
<center>
<a href="?subtopic=guilds&amp;action=show&amp;guild='.$result['guild_id'].'">'.$result['guild_name'].'</a>
</center>
</td>

<td width="30%">
<center>'.$result['date'].'
</center>
</td>
</tr></tbody></table>
</div>
										<div class="TableShadowContainer">
											<div class="TableBottomShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bm.gif);">
											<div class="TableBottomLeftShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bl.gif);"></div>
											<div class="TableBottomRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-br.gif);"></div>
										</div>
									</div></td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<br>';
?>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-vertical.gif);"></span>
			<div class="Text">Informações sobre o Castle 24 Horas</div>
			<span class="CaptionVerticalRight" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/box-frame-edge.gif);"></span>
		</div>
	</div>
	<table class="Table5" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td>
					<div class="InnerTableContainer">
						<table style="width:100%;">
							<tbody>
								<tr>
									<td>
										<div class="TableShadowContainerRightTop">
											<div class="TableShadowRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rt.gif);"></div>
										</div>
										<div class="TableContentAndRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rm.gif);">
										<div class="TableContentContainer">
										<table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
										<tbody><tr>
										<td><b>Horários:</b> O castelo é aberto 24 Horas por dia.</td>
										</tr>									
										</tbody></table>
										</div>
									
													<div class="TableShadowContainer">
														<div class="TableBottomShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bm.gif);">
															<div class="TableBottomLeftShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bl.gif);"></div>
															<div class="TableBottomRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-br.gif);"></div>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="TableShadowContainerRightTop">
														<div class="TableShadowRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rt.gif);"></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rm.gif);">
														<div class="TableContentContainer">
															<table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
																<tbody><tr>
																	<td><b>Como funciona o evento?</b><br> É necessário que você tenha uma guild e seja level maior que 100 para entrar no castelo.<br>Você terá acesso ao castelo por qualquer NPC de barco, <u>hi, castle war, yes</u>.</td>
																</tr>						
															</tbody></table>
														</div>
													</div>	

													<div class="TableShadowContainer">
														<div class="TableBottomShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bm.gif);">
															<div class="TableBottomLeftShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bl.gif);"></div>
															<div class="TableBottomRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-br.gif);"></div>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="TableShadowContainerRightTop">
														<div class="TableShadowRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rt.gif);"></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rm.gif);">
														<div class="TableContentContainer">
															<table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
																<tbody><tr>
																	<td><b>Objetivo: </b>O objetivo do evento é você e sua guild derrotar o Castle Crystal. Todo player que atravessar o limite de domínio do castelo mandará uma mensagem para todos do server que o castelo está sendo invadido pela guild "X", dando um tempo necessário para os atuais donos defenderem o castelo. Sendo necessário matar alguns gates para liberar a passagem até chegar no castle crystal.<br><i><b>OBS¹:</b></i> Todos os membros da guild dominante podem usar uma alavanca para liberar as passagens.<br><i><b>OBS²:</b></i> O Castle crystal tem um respawn instantaneo ao morrer.<br><i><b>OBS³: </b></i>Os membros da guild dominante não podem atacar o Castle Crystal.<br><br>
																	<center><a class="fancybox-media" rel="group1" title="Local onde o 1º Gate se encontra." href="/images/CastleWar24/castle1.png">
																		<img src="/images/CastleWar24/castle1.png" width="130px" border="1">
																	</a>
																	<a class="fancybox-media" rel="group1" title="Local onde o 2º Gate se encontra." href="/images/CastleWar24/castle2.png">
																		<img src="/images/CastleWar24/castle2.png" width="130px" border="1">
																	</a>
																	<a class="fancybox-media" rel="group1" title="Local onde o 3º Gate se encontra." href="/images/CastleWar24/castle3.png">
																		<img src="/images/CastleWar24/castle3.png" width="130px" border="1">
																	</a>
																	<a class="fancybox-media" rel="group1" title="Local onde o 4º Gate se encontra." href="/images/CastleWar24/castle4.png">
																		<img src="/images/CastleWar24/castle4.png" width="130px" border="1">
																	</a>
																	<a class="fancybox-media" rel="group1" title="Local onde o Castle Crystal se encontra." href="/images/CastleWar24/castle5.png">
																		<img src="/images/CastleWar24/castle5.png" width="130px" border="1">
																	</a></center>
																	<i><small>Clique nas imagens para ampliar</small></i></td>
																</tr>									
															</tbody></table>
														</div>
													</div>											
													<div class="TableShadowContainer">
														<div class="TableBottomShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bm.gif);">
															<div class="TableBottomLeftShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bl.gif);"></div>
															<div class="TableBottomRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-br.gif);"></div>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="TableShadowContainerRightTop">
														<div class="TableShadowRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rt.gif);"></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rm.gif);">
														<div class="TableContentContainer">
															<table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
																<tbody><tr>
																	<td><b>Prêmio:</b> Área do castelo com hunts exclusivas para a guild dominante.</td>
																</tr>									
															</tbody></table>
														</div>
													</div>											
													<div class="TableShadowContainer">
														<div class="TableBottomShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bm.gif);">
															<div class="TableBottomLeftShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bl.gif);"></div>
															<div class="TableBottomRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-br.gif);"></div>
														</div>
													</div>
												</td>
											</tr>

											<tr>
												<td>
													<div class="TableShadowContainerRightTop">
														<div class="TableShadowRightTop" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rt.gif);"></div>
													</div>
													<div class="TableContentAndRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-rm.gif);">
														<div class="TableContentContainer">
															<table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
																<tbody><tr>
																	<td width="50%"><b>Outras imagens:</b><br><br>
																	<center><a class="fancybox-media" rel="group2" title="Área do Castelo." href="/images/CastleWar24/castle6.jpg">
																		<img src="/images/CastleWar24/castle6.jpg" width="114px" border="1">
																	</a>
																	<a class="fancybox-media" rel="group2" title="Área de Hunts do Castle." href="/images/CastleWar24/castle7.png">
																		<img src="/images/CastleWar24/castle7.png" width="190px" border="1">
																	</a>
																	<a class="fancybox-media" href="/images/CastleWar24/castle8.png">
																		<img src="/images/CastleWar24/castle8.png" width="190px" border="1">
																	</a>
																	<a class="fancybox-media" rel="group2" title="Área de Hunts do Castle." href="/images/CastleWar24/castle9.png">
																		<img src="/images/CastleWar24/castle9.png" width="190px" border="1">
																	</a></center>
																	<br>
																	</td>
																</tr>									
															</tbody></table>
														</div>
													</div>											
													<div class="TableShadowContainer">
														<div class="TableBottomShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bm.gif);">
															<div class="TableBottomLeftShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-bl.gif);"></div>
															<div class="TableBottomRightShadow" style="background-image:url(<?php echo $layout_name; ?>/images/global/content/table-shadow-br.gif);"></div>
														</div>
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
</div>
</div>