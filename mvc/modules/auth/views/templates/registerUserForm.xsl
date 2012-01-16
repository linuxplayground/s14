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
				<h2>Super 14 - User Registration</h2>
				<hr />
				<span class='main'>Message: </span><span class='message'><xsl:value-of select='form/message' /></span>
				<table width='90%'>
					<tr>
						<td valign='top' width="15%" >
							<xsl:call-template name='general_menu' />
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
		<em>New User Registration Form</em>
		<form name='registerUser'>
			<xsl:attribute name='action'><xsl:text>index.php?action=insertNewUser&amp;module=auth</xsl:text></xsl:attribute>
			<xsl:attribute name='method'>POST</xsl:attribute>
			<table>
				<tr>
					<td colspan="2" align="left">
						<span class="main">Please fill in all of these fields.
							<ul>
								<li>Email addresses at this stage are not used.  They will be used later on to email you your picks and notifications of winnings.</li>
								<li>Passwords are stored in plain text so if you forget it just ask the administrator.</li>
								<li>Once you have logged in, use the news area on the front page for help.</li>
							</ul>
						</span>	
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<ul>
							<xsl:apply-templates select='errorMsg'/>
						</ul>
					</td>
				</tr>
				<tr>
					<td width="250px"><span class="main">User Name (use this to login - eg: davidl</span></td>
					<td width="250px"><span class="main">Password (whatever you like.)</span></td>
				</tr>
				<tr>
					<td valign="top">
						<input type="text" name="regUserName" id="regUserName">
							<xsl:attribute name='value'>
								<xsl:value-of select='regUserName' />
							</xsl:attribute>
						</input>
					</td>
					<td valign="top">
						<input type="password" name="regPassword1" id="regPassword1"/> <span class="main">Password</span>
						<br /><br />
						<input type="password" name="regPassword2" id="regPassword2"/> <span class="main">And again</span>
					</td>
				</tr>
				<tr>
					<td><span class="main">First Name</span></td>
					<td><span class="main">Last Name</span></td>
				</tr>
				<tr>
					<td>
						<input type="text" name="regFirstName" id="regFirstName">
							<xsl:attribute name='value'>
								<xsl:value-of select='regFirstName' />
							</xsl:attribute>
						</input>
					</td>
					<td>
						<input type="text" name="regLastName" id="regLastName">
							<xsl:attribute name='value'>
								<xsl:value-of select='regLastName' />
							</xsl:attribute>
						</input>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left">
						<span class="main">Email Addres (Your work email address)</span>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left">
						<input type="text" name="regEmailAddress" id="regEmailAddress">
							<xsl:attribute name='value'>
								<xsl:value-of select='regEmailAddress'/>
							</xsl:attribute>
						</input>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<input>
							<xsl:attribute name='name'>regUserSubmit</xsl:attribute>
							<xsl:attribute name='id'>regUserSubmit</xsl:attribute>
							<xsl:attribute name='value'>Submit...</xsl:attribute>
							<xsl:attribute name='type'>submit</xsl:attribute>
						</input>
					</td>
				</tr>
			</table>
		</form>
	</xsl:template>
	
	<xsl:template match = 'errorMsg'>
		<span style='color:#f00'><li><xsl:value-of select='.'/></li></span>
	</xsl:template>
</xsl:stylesheet>