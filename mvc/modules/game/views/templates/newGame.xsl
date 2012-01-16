<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
	<xsl:include href='../../../common/templates/includes/footer.xsl' />
	<xsl:include href='../../../common/templates/includes/admin_menu.xsl' />
	
	<xsl:template match='/'>
		<html>
			<head>
				<title>Super14 Sweepstakes</title>
				<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
				<!-- BEGIN CALENDAR -->
					<style type="text/css">@import url(modules/common/javascript/calendar/skins/aqua/theme.css);</style>
					<script type="text/javascript" src="modules/common/javascript/calendar/calendar_stripped.js"></script>
					<script type="text/javascript" src="modules/common/javascript/calendar/lang/calendar-en.js"></script>
					<script type="text/javascript" src="modules/common/javascript/calendar/calendar-setup.js"></script>
				<!-- END CALENDAR -->
			</head>
			<body>
				<h2>Super 14 - Games</h2>
				<hr />
				<span class='main'>Message: </span><span class='message'><xsl:value-of select='page/message' /></span>
				<table width='90%'>
					<tr>
						<td valign='top' width='15%'>
							<xsl:call-template name="general_menu" />
						</td>
						<td valign='top' width='15%'>
							<xsl:call-template name="admin_menu" />
						</td>
						<td valign='top'>
							<xsl:apply-templates select="form" />
						</td>
					</tr>
				</table>
			<xsl:call-template name='footer' />
			</body>
		</html>	
	</xsl:template>
	
	<xsl:template match='form'>
		<em><xsl:value-of select='label'/></em>
		<form name='editGame'>
			<xsl:attribute name='action'><xsl:value-of select='action'/></xsl:attribute>
			<xsl:attribute name='method'><xsl:value-of select='method'/></xsl:attribute>
			<table>
				<tr>
					<td>Home Team</td>
					<td>Away Team</td>
				</tr>
				<tr>
					<td>
						<xsl:element name="select">
						  <xsl:attribute name="name">homeTeamId</xsl:attribute>
						    <option value="top">Select Team</option>
						    <xsl:for-each select="team">
						        <option>
						        	<xsl:attribute name='value'><xsl:value-of select="id"/></xsl:attribute>
						        	<xsl:if test="/form/homeTeam/id = id">
						        		<xsl:attribute name='SELECTED'><xsl:text>SELECTED</xsl:text></xsl:attribute>
						        	</xsl:if>
						        	<xsl:value-of select="name"/>
						        </option>
						    </xsl:for-each>     
						</xsl:element>
					</td>
					<td>
						<xsl:element name="select">
						  <xsl:attribute name="name">awayTeamId</xsl:attribute>
						    <option value="top">Select Team</option>
						    <xsl:for-each select="team">
						        <option>
						        	<xsl:attribute name='value'><xsl:value-of select="id"/></xsl:attribute>
						        	<xsl:if test="/form/awayTeam/id = id">
						        		<xsl:attribute name='SELECTED'><xsl:text>SELECTED</xsl:text></xsl:attribute>
						        	</xsl:if>
						        	<xsl:value-of select="name"/>
						        </option>
						    </xsl:for-each>     
						</xsl:element>
					</td>
				</tr>
				<tr>
					<td>Match Date</td>
					<td>Match Type</td>
				</tr>
				<tr>
					<td>
						<INPUT TYPE="text" NAME="matchDate" id="matchDate" SIZE="25">
							<xsl:attribute name='value'>
								<xsl:value-of select='date'/>
							</xsl:attribute>
						</INPUT>
						<button type='reset' id='calendarTrigger'>...</button>
<!-- BEGIN CALENDAR -->
<script type="text/javascript">
<![CDATA[
Calendar.setup(
	{
	  inputField  : "matchDate",         // ID of the input field
	  ifFormat    : "%Y-%m-%d %H:%M",    // the date format
	  showsTime	  : true,
	  timeFormat  : "24",
	  button      : "calendarTrigger"       // ID of the button
	}
);]]>
</script>
<!-- END CALENDAR -->
					</td>
					<td>
						<xsl:element name="select">
						  <xsl:attribute name="name">matchTypeId</xsl:attribute>
						    <option value="top">Select Match Type</option>
						    <xsl:for-each select="matchType">
						        <option>
						        	<xsl:attribute name='value'><xsl:value-of select="id"/></xsl:attribute>
						        	<xsl:if test="/form/matchTypeId = id">
						        		<xsl:attribute name='SELECTED'><xsl:text>SELECTED</xsl:text></xsl:attribute>
						        	</xsl:if>
						        	<xsl:value-of select="name"/>
						        </option>
						    </xsl:for-each>     
						</xsl:element>
					</td>
				</tr>
				<tr>
					<td>Round Number</td>
					<td>
						<input>
							<xsl:attribute name='name'>gameRoundNum</xsl:attribute>
							<xsl:attribute name='id'>gameRoundNum</xsl:attribute>
							<xsl:attribute name='type'>text</xsl:attribute>
							<xsl:attribute name='size'>3</xsl:attribute>
						</input>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<input>
							<xsl:attribute name='name'>editGameSubmit</xsl:attribute>
							<xsl:attribute name='id'>editGameSubmit</xsl:attribute>
							<xsl:attribute name='value'>Submit...</xsl:attribute>
							<xsl:attribute name='type'>submit</xsl:attribute>
						</input>
					</td>
				</tr>
			</table>
		</form>
	</xsl:template>
</xsl:stylesheet>