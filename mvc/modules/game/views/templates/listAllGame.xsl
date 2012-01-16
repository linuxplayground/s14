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
			<script language="javascript">
			<![CDATA[
function menu_goto( v )
{
    if (v.length != 0) {
      location.href = 'index.php?action=listAllGame&module=game&year=' + v ;
    }
}
function menu_goto_round( v )
{
    if (v.length != 0) {
      location.href = 'index.php?action=listAllGame&module=game&round=' + v ;
    }
}
			]]>
			</script>
		</head>
		<body>
			<h2>Super 14 - Games</h2>
			<hr />
			<span class='main'>Message: </span><span class='message'><xsl:value-of select='page/message' /></span>
			<ul>
				<li><span class='main'>All Dates and times in New Zealand Time please.</span></li>
			</ul>
			<table width='90%'>
				<tr>
					<td valign='top' width='15%'>
						<xsl:call-template name="general_menu" />
					</td>
					<td valign='top' width='15%'>
						<xsl:call-template name="admin_menu" />
					</td>
					<td valign='top'>
						<form>
							<xsl:attribute name="action"><xsl:text>index.php?action=updateGameScores&amp;module=game</xsl:text></xsl:attribute>
							<xsl:attribute name="method"><xsl:text>POST</xsl:text></xsl:attribute>
							<xsl:attribute name="id"><xsl:text>gamesForm</xsl:text></xsl:attribute>
							<table cellspacing='1'  bgcolor='#6699cc'>
								<tr>
									<th>Home</th>
									<th>Away</th>
									<th>H Score</th>
									<th>A Score</th>
									<th>Date</th>
									<th>Match Type</th>
									<th>Round</th>
									<th>Actions</th>
								</tr>
								<xsl:apply-templates select='page/game' />
							</table>
							<br />
							<button>
								<xsl:attribute name='onClick'>
									<![CDATA[window.location='index.php?action=newGame&module=game'; return false;]]>
								</xsl:attribute>
								New
							</button>
							<button>
								<xsl:attribute name='onClick'>
									<![CDATA[window.location='index.php?action=listMatchType&module=matchType'; return false;]]>
								</xsl:attribute>
								Match Types
							</button>
							<xsl:if test="page/showUpdateButton &gt; 0">
								<input type='submit' name='updateScoresSubmit' id='updateScoresSubmit' value='Update Scores' />
							</xsl:if>
							<button>
								<xsl:attribute name='onClick'>
									<![CDATA[window.location=']]><xsl:value-of select='page/pdfurl'/><![CDATA['; return false;]]>
								</xsl:attribute>
								PDF
							</button>
							<br/>
							Filter by year:
							<xsl:element name='select'>
								<xsl:attribute name='name'><xsl:text>yearNav</xsl:text></xsl:attribute>
								<xsl:attribute name='id'><xsl:text>yearNav</xsl:text></xsl:attribute>
								<xsl:attribute name='onchange'>
									<![CDATA[menu_goto( this.options[this.selectedIndex].value );return false;]]>
								</xsl:attribute>
								<option value='all'>Show All Years</option>
								<xsl:apply-templates select='page/years/value' />
							</xsl:element>
							Filter by round:
							<xsl:element name='select'>
								<xsl:attribute name='name'><xsl:text>roundNav</xsl:text></xsl:attribute>
								<xsl:attribute name='id'><xsl:text>yroundNav</xsl:text></xsl:attribute>
								<xsl:attribute name='onchange'>
									<![CDATA[menu_goto_round( this.options[this.selectedIndex].value );return false;]]>
								</xsl:attribute>
								<option value='all'>Show All Rounds</option>
								<xsl:apply-templates select='page/round' />
							</xsl:element>
						</form>
					</td>
				</tr>
			</table>
			<xsl:call-template name='footer' />
		</body>
	</html>
	
</xsl:template>

<xsl:template match="page/years/value">
	<option>
		<xsl:attribute name='value'>
			<xsl:value-of select='.'/>
		</xsl:attribute>
		<xsl:if test='. = /page/defaultYear'>
			<xsl:attribute name='selected'>
				selected
			</xsl:attribute>
		</xsl:if>
		<xsl:value-of select='.' />
	</option>
</xsl:template>

<xsl:template match="page/round">
	<option>
		<xsl:attribute name='value'>
			<xsl:value-of select='.'/>
		</xsl:attribute>
		<xsl:if test='. = /page/defaultRound'>
			<xsl:attribute name='selected'>
				selected
			</xsl:attribute>
		</xsl:if>
		<xsl:value-of select='.' />
	</option>
</xsl:template>

<xsl:template match="page/game">
		<tr>
			<td>
				<xsl:value-of select='hometeam'/>
			</td>
			<td>
				<xsl:value-of select='awayteam'/>
			</td>
			<td>
				<xsl:choose>
					<xsl:when test="scored = 1">
						<xsl:value-of select='homescore'/>
					</xsl:when>
					<xsl:otherwise>
						<input type='text' maxlength='3' size='4'>
							<xsl:attribute name="name">updateHomeScore[<xsl:value-of select='id' />]</xsl:attribute>
							<xsl:attribute name="id">updateHomeScore[<xsl:value-of select='id' />]</xsl:attribute>
						</input>
					</xsl:otherwise>
				</xsl:choose>
			</td>
			<td>
				<xsl:choose>
					<xsl:when test="scored = 1">
						<xsl:value-of select='awayscore'/>
					</xsl:when>
					<xsl:otherwise>
						<input type='text' maxlength='3' size='4'>
							<xsl:attribute name="name">updateAwayScore[<xsl:value-of select='id' />]</xsl:attribute>
							<xsl:attribute name="id">updateAwayScore[<xsl:value-of select='id' />]</xsl:attribute>
						</input>
					</xsl:otherwise>
				</xsl:choose>
			</td>
			<td>
				<xsl:value-of select='date' />
			</td>
			<td>
				<xsl:value-of select='matchtype' />
			</td>
			<td>
				<xsl:value-of select="round"></xsl:value-of></td>
			<td>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=editGame&module=game&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Edit
				</button>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=confirmDeleteGame&module=game&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Delete
				</button>	
			</td>
		</tr>
</xsl:template>

</xsl:stylesheet>