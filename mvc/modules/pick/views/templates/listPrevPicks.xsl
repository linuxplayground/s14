<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
<xsl:include href='../../../common/templates/includes/footer.xsl' />

<xsl:template match="/">
	
	<html>
		<head>
			<title>Super14 Sweepstakes</title>
			<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
			<script language='javascript' type='text/javascript'>
			<![CDATA[
				function menu_goto( v )
				{
				    if (v.length != 0) {
				      location.href = 'index.php?action=getPreviousPicks&module=pick&round=' + v ;
				    }
				}
			]]>
			</script>
		</head>
		<body>
			<h2>Super 14 - Previous Picks</h2>
			<hr />
			<span class='main'>Message: </span><span class='message'><xsl:value-of select='picksTable/message' /></span>
			<table width='90%'>
				<tr>
					<td valign='top' width='15%'>
						<xsl:call-template name="general_menu" />
					</td>
					<td valign='top'>
						<xsl:apply-templates select='picksTable/matchTypePicks' />
					</td>
				</tr>
			</table>
			<xsl:call-template name='footer' />
		</body>
	</html>
</xsl:template>

<xsl:template match="matchTypePicks">
	<form id='pickForm' name='pickForm'>
		<table  cellspacing='1'  bgcolor='#6699cc'>
		<xsl:attribute name='name'>picks[<xsl:value-of select="roundNumber"/>]</xsl:attribute>
		<xsl:attribute name='id'>picks[<xsl:value-of select="roundNumber"/>]</xsl:attribute>
			<tr>
				<th><xsl:value-of select='matchTypeName'/></th>
				<th>Round: <xsl:value-of select='roundNumber' /></th>
				<th>
					<xsl:attribute name='colspan'>
						<xsl:value-of select='count(margin)'/>
					</xsl:attribute>
					<xsl:if test=' bonusName!="" '>
						<xsl:attribute name='style'>
							background-color: #f00;
							color:#fff;
						</xsl:attribute>
						Bonus Week: <xsl:value-of select='bonusName'/>
					</xsl:if>
				</th>
				<th> </th>
			</tr>
			<tr>
				<th width='150'>Date</th>
				<th width='150'>Home</th>
				<xsl:apply-templates select='margin' />
				<th width='150'>Away</th>
			</tr>
			<xsl:apply-templates select='game' />
		</table>
		Select Round: 
		<select name='roundSelect' id='roundSelect'>
			<xsl:attribute name='onchange'>
				<![CDATA[menu_goto( this.options[this.selectedIndex].value );return false;]]>
			</xsl:attribute>
			<xsl:apply-templates select='/picksTable/round' />
		</select>
	</form>
</xsl:template>

<xsl:template match='picksTable/round'>
	<option>
		<xsl:attribute name='value'>
			<xsl:value-of select='.'/>
		</xsl:attribute>
		<xsl:if test='. = /picksTable/matchTypePicks/roundNumber'>
			<xsl:attribute name='selected'>
				selected
			</xsl:attribute>
		</xsl:if>
		<xsl:value-of select='.' />
	</option>
</xsl:template>

<xsl:template match='picksTable/matchTypePicks/game'>
	<tr>
		<xsl:attribute name='id'>game</xsl:attribute>
		<td><xsl:value-of select='date' /></td>
		<td><xsl:value-of select ='hometeam' /></td>
		<xsl:variable name='game_id' select='gameId' />
		<xsl:variable name='pickedMargin' select='picked' />
		<xsl:variable name='roundNumber' select='round' />
		<xsl:for-each select='../margin'>
			<td align='center' bgcolor = '#ccf'>
				<xsl:if test="$pickedMargin=id">
					<input type='radio'>
						<xsl:attribute name='name'>
							<xsl:text>margin[</xsl:text>
							<xsl:value-of select='$game_id'/>
							<xsl:text>]</xsl:text>
						</xsl:attribute>
						<xsl:attribute name='id'>
							<xsl:text>margin[</xsl:text>
							<xsl:value-of select='$game_id'/>
							<xsl:text>]</xsl:text>
						</xsl:attribute>
						<xsl:attribute name='value'><xsl:value-of select='id' /></xsl:attribute>
						<xsl:attribute name="CHECKED"><xsl:text>CHECKED</xsl:text></xsl:attribute>
						<xsl:attribute name='disabled'>
							disabled
						</xsl:attribute>
					</input>
				</xsl:if>
				<xsl:if test="not($pickedMargin=id)">
					<input type='radio'>
						<xsl:attribute name='name'>
							<xsl:text>margin[</xsl:text>
							<xsl:value-of select='$game_id'/>
							<xsl:text>]</xsl:text>
						</xsl:attribute>
						<xsl:attribute name='id'>
							<xsl:text>margin[</xsl:text>
							<xsl:value-of select='$game_id'/>
							<xsl:text>]</xsl:text>
						</xsl:attribute>
						<xsl:attribute name='value'><xsl:value-of select='id' /></xsl:attribute>
						<xsl:attribute name='disabled'>
							disabled
						</xsl:attribute>
					</input>
				</xsl:if>
			</td>
		</xsl:for-each>
		<td><xsl:value-of select='awayteam' /></td>
	</tr>
</xsl:template>

<xsl:template match='picksTable/matchTypePicks/margin'>
	<th align='center' width='50px'><xsl:value-of select='name' /></th>
</xsl:template>

</xsl:stylesheet>