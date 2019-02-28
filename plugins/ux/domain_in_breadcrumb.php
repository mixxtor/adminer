<?php

/** Add current domain to breadcrumb
* @link https://www.adminer.org/plugins/#use
* @author SailorMax, http://www.sailormax.net/
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerDomainInBreadcrumb
{
	function head()
	{
?>
		<script<?=nonce()?>>
		document.addEventListener("DOMContentLoaded", function(event)
		{
			var breadcrumb = document.getElementById("breadcrumb");
			if (breadcrumb)
			{
				var span = document.createElement("SPAN");
				span.innerHTML = document.domain + " » ";
				breadcrumb.insertBefore(span, breadcrumb.childNodes[0]);
			}
		});
		</script>
<?php
	}
}