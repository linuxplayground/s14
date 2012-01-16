<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template match='form'>
		<span class='formHeading'><i><xsl:value-of select='label' /></i></span>
		<form>
			<xsl:attribute name='action'>
				<xsl:value-of select='action' />
			</xsl:attribute>
			<xsl:attribute name='method'>
				<xsl:value-of select='method' />
			</xsl:attribute>
			<table>
				<xsl:apply-templates select='input' />
				<xsl:apply-templates select='select' />
				<xsl:apply-templates select='textArea' />
				<tr>
					<td colspan='2'>
						<xsl:apply-templates select='submit' />
					</td>
				</tr>
			</table>
			
		</form>
	</xsl:template>
	
	<xsl:template match='input'>
		<tr>
			<td>
				<input>
					<xsl:attribute name='type'>
						<xsl:value-of select='type' />
					</xsl:attribute>
					<xsl:attribute name='name'>
						<xsl:value-of select='name' />
					</xsl:attribute>
					<xsl:attribute name='id'>
						<xsl:value-of select='name' />
					</xsl:attribute>
					<xsl:attribute name='value'>
						<xsl:value-of select='value' />
					</xsl:attribute>
				</input>
			</td>
			<td>
				<xsl:value-of select='label' />
			</td>
		</tr>
	</xsl:template>
	
	<xsl:template match='textArea'>
		<tr>
			<td>
				<textarea>
					<xsl:attribute name='rows'>15</xsl:attribute>
					<xsl:attribute name='cols'>50</xsl:attribute>
					<xsl:attribute name='name'>
						<xsl:value-of select='name' />
					</xsl:attribute>
					<xsl:attribute name='id'>
						<xsl:value-of select='name' />
					</xsl:attribute>
				<xsl:value-of disable-output-escaping='yes' select='content' />
				</textarea>
			</td>
			<td>
				<xsl:attribute name='valign'>top</xsl:attribute>
				<xsl:value-of select='label' />
			</td>
		</tr>
	</xsl:template>

	<xsl:template match='select'>
		<tr>
			<td>
				<xsl:element name='select'>
					<xsl:attribute name='name'><xsl:value-of select='name'/></xsl:attribute>
					<xsl:attribute name='id'><xsl:value-of select='name'/></xsl:attribute>
					<option value="top">Select <xsl:value-of select='label' /></option>
					<xsl:apply-templates select='option'/>
				</xsl:element>
			</td>
			<td>
				<xsl:value-of select='label' />
			</td>
		</tr>
	</xsl:template>
	
	<xsl:template match='option'>
		<option>
	       <xsl:attribute name='value'><xsl:value-of select="value"/></xsl:attribute>
        	<xsl:if test="/form/select/defaultValue = value">
        		<xsl:attribute name='SELECTED'><xsl:text>SELECTED</xsl:text></xsl:attribute>
        	</xsl:if>
        	<xsl:value-of select="label"/>
       	</option>
	</xsl:template>
	
	<xsl:template match='submit'>
		<input>
			<xsl:attribute name='type'>
				<xsl:text>Submit</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='name'>
				<xsl:value-of select='name' />
			</xsl:attribute>
			<xsl:attribute name='id'>
				<xsl:value-of select='name' />
			</xsl:attribute>
			<xsl:attribute name='value'>
				<xsl:value-of select='value' />
			</xsl:attribute>
		</input>
	</xsl:template>
	
</xsl:stylesheet>