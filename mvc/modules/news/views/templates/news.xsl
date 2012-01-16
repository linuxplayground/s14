<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
<xsl:include href='../../../common/templates/includes/footer.xsl' />

<xsl:template match="/">
	
	<html>
		<head>
			<title>Super14 Sweepstakes</title>
			<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
			<h2>Super 14 - News</h2>
			<hr />
			<span class='main'>Message: </span><span class='message'><xsl:value-of select='page/message' /></span>
			<table width='90%'>
				<tr>
					<td valign='top' width='15%'>
						<xsl:call-template name="general_menu" />
					</td>
					<td valign='top'>
						<xsl:apply-templates select='page/news' />
						<button>
							<xsl:attribute name='onClick'>
								<![CDATA[window.location='index.php?action=newNews&module=news']]>
							</xsl:attribute>
							New
						</button>
					</td>
				</tr>
			</table>
			<xsl:call-template name='footer' />
		</body>
	</html>
	
</xsl:template>


<xsl:template match="page/news">
	<table>
		<tr>
			<td>
				<xsl:attribute name='width'><xsl:text>400px</xsl:text></xsl:attribute>
				<b><xsl:value-of select='title' /></b>
				<p>
				<xsl:value-of disable-output-escaping='yes' select='body' />
				</p>
				<p>
				<xsl:value-of select='date' /> 
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=editNews&module=news&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Edit
				</button>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=confirmDeleteNews&module=news&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Delete
				</button>
				</p>
				<hr>
					<xsl:attribute name='width'><xsl:text>300px</xsl:text></xsl:attribute>
					<xsl:attribute name='align'><xsl:text>left</xsl:text></xsl:attribute>
				</hr>
			</td>
		</tr>
	</table>
</xsl:template>

</xsl:stylesheet>