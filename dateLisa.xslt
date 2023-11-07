<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:msxsl="urn:schemas-microsoft-com:xslt" exclude-result-prefixes="msxsl">
	<xsl:output method="html" indent="yes"/>

	<xsl:template match="/">
		<h2>Andmed xml failist tootajad.xml:</h2>
		<table>
			<tr>
				<th>Nimi</th>
				<th>Isikukood</th>
				<th>Aeg</th>
			</tr>
			<xsl:for-each select="//date/aeg">
				<tr>
					<td><xsl:value-of select="tootaja/nimi"/></td>
					<td><xsl:value-of select="tootaja/isikukood"/></td>
					<td><xsl:value-of select="@aeg"/></td>
				</tr>
			</xsl:for-each>
		</table>
	</xsl:template>
</xsl:stylesheet>
