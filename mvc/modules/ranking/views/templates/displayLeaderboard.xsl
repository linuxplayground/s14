<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
<xsl:include href='../../../common/templates/includes/footer.xsl' />
<xsl:include href='../../../common/templates/includes/admin_menu.xsl' />

<xsl:template match="/">
	
	<html>
		<head>
			<title>Super14 Sweepstakes</title>
			<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
			<h2>Super 14 - Leaderboard</h2>
			<hr />
			<span class='main'>Message: </span><span class='message'><xsl:value-of select='/rank/message' /></span>
			<table width='90%'>
				<tr>
					<td valign='top' width='15%'>
						<xsl:call-template name="general_menu" />
					</td>
					<td valign='top'>
						<table cellspacing='1'  bgcolor='#6699cc' cellpadding='5'>
							<tr>
								<th rowspan='2' valign='middle'>Players</th>
								<th>
									<xsl:attribute name='colspan'>
										<xsl:value-of select='/rank/numberofrounds'/>
									</xsl:attribute>
									Rounds
								</th>
								<th rowspan='2' valign='middle'><a href="index.php?action=leaderboard&amp;module=ranking&amp;sortround=total">Totals</a></th>
							</tr>
							<tr>
								<script language='javascript'>
<![CDATA[
	var bonusWeeks = Array();
	]]>
	<xsl:for-each select='/rank/round'>
		<![CDATA[bonusWeeks[]]><xsl:value-of select='number'/><![CDATA[] = ']]><xsl:value-of select='bonusName'/><![CDATA[';]]>
	</xsl:for-each>
	<![CDATA[
	for (lp=0; lp<]]><xsl:value-of select='/rank/numberofrounds'/><![CDATA[; lp++) {
		if (bonusWeeks[eval(lp+1)] != '') {
			document.write('<th style="background-color:#FFA6A6;"><a href="index.php?action=leaderboard&module=ranking&sortround=' + eval(lp+1)+ '">'+eval(lp+1)+' '+bonusWeeks[eval(lp+1)]+'</a></th>');
		} else {
			document.write('<th><a href="index.php?action=leaderboard&module=ranking&sortround=' + eval(lp+1)+ '">'+eval(lp+1)+'</a></th>');
		}
	}
]]>
								</script>
							</tr>
							<xsl:variable name='sortRound' select='/rank/lastRound'/>
							<xsl:choose>
								<xsl:when test = '$sortRound = "total" '>
									<xsl:apply-templates select='/rank/user'>
										<xsl:sort select='total' data-type='number' order='descending' />
									</xsl:apply-templates>
								</xsl:when>
								<xsl:otherwise>
									<xsl:apply-templates select='/rank/user'>
										<xsl:sort select='round[position()=$sortRound]/score' data-type='number' order='descending' />
									</xsl:apply-templates>
								</xsl:otherwise>
							</xsl:choose>
							<tr style='height:2px;'></tr>
							<tr>
								<td><b>Take</b></td>
	<xsl:for-each select='/rank/take'>
		<td>$<xsl:value-of select = '.'/>.00</td>
	</xsl:for-each>
								<td align='center'><b>$<xsl:value-of select='sum(/rank/take)'/>.00</b></td>
							</tr>
						</table>
<!--
						<button name='pdf' id='pdf'>
							<xsl:attribute name='onclick'>document.location.href = 'index.php?action=leaderboardPdf&amp;module=ranking';</xsl:attribute>
							PDF
						</button> NOTE: Results in pdf will not be sorted according to last round.  This is a bug.
-->
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<i>Note: a red <font color='#ff0000'>0</font> indicates that a user did not place a pick for that round.</i>
					</td>
				</tr>
			</table>
			
			<xsl:call-template name='footer' />
		</body>
	</html>
</xsl:template>

<xsl:template match='rank/user'>
	<tr>
		<td><xsl:value-of select='name'/></td>
		<xsl:for-each select='./round'>
			<td align='center'>
				<font>
					<xsl:attribute name = 'color'>
						<xsl:value-of select = 'score_colour'/>
					</xsl:attribute>
					<xsl:value-of select='score'/>
				</font>
			</td>
		</xsl:for-each>
		<td align='center'><b><xsl:value-of select='sum(./round/score)'/></b></td>
	</tr>
</xsl:template>

</xsl:stylesheet>