<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
	<xsl:include href='../../../common/templates/includes/footer.xsl' />
	<xsl:include href='../../../common/templates/includes/admin_menu.xsl' />
	
	<xsl:template match='/'>
		<html>
			<head>
				<title>Super14 Sweepstakes</title>
				<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
			</head>
			<body>
				<h2>Super 14 - Bonus</h2>
				<hr />
				<table width='90%'>
					<tr>
						<td valign='top' width="15%" >
							<xsl:call-template name='general_menu' />
						</td>
						<td valign='top' width="15%" >
							<xsl:call-template name='admin_menu' />
						</td>
						<td valign='top'>
							<xsl:apply-templates select='form' />
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
					<td>Bonus Name</td>
					<td>Multiplier</td>
					<td>Round Number</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="bonusName" id="bonusMultiplier">
							<xsl:attribute name='value'>
								<xsl:value-of select='name' />
							</xsl:attribute>
						</input>
					</td>
					<td>
						<input type="text" name="bonusMultiplier" id="bonusMultiplier">
							<xsl:attribute name='value'>
								<xsl:value-of select='multiplier' />
							</xsl:attribute>
						</input>
					</td>
					<td>
						<INPUT TYPE="text" NAME="roundNumber" id="roundNumber">
							<xsl:attribute name='value'>
								<xsl:value-of select='roundNumber'/>
							</xsl:attribute>
						</INPUT>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<input>
							<xsl:attribute name='name'>bonusSubmit</xsl:attribute>
							<xsl:attribute name='id'>bonusSubmit</xsl:attribute>
							<xsl:attribute name='value'>Submit...</xsl:attribute>
							<xsl:attribute name='type'>submit</xsl:attribute>
						</input>
					</td>
				</tr>
			</table>
		</form>
	</xsl:template>
</xsl:stylesheet>