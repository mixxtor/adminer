<?php
define("CURRENT_DIR", __DIR__);

function adminer_object()
{
    // required to run any plugin
    include_once CURRENT_DIR."/plugins/plugin.php";

    // autoloader
    foreach (glob(CURRENT_DIR."/plugins/*.php") as $filename) {
        include_once $filename;
    }

    $plugins = array(
        // specify enabled plugins here
        new AdminerFileUpload("data/"),
        new AdminerSlugify,
        new AdminerForeignSystem,
    );

	include_once (CURRENT_DIR."/plugins/ux/_php_my_admin_plugins.php");
	$plugins += $_php_my_admin_plugins;

	// my
	class MyAdminerModifies
	{
		function head()
		{
			// manual link with default scripts/css
			// manual use skin
//			<link rel="stylesheet" type="text/css" href="adminer/static/default.css">
?>
			<link rel="stylesheet" type="text/css" href="adminer.css" />
			<script type="text/javascript" src="adminer/static/functions.js"></script>
			<script type="text/javascript" src="adminer/static/editing.js"></script>
			<script>
			document.addEventListener("DOMContentLoaded", function(event)
			{
				var inputs = document.getElementsByTagName("INPUT");
				var i, cnt = inputs.length;
				for (i=0; i<cnt; i++)
					if (inputs[i].type == "image")
					{
//						inputs[i].src = inputs[i].src.replace("/adminer/", "/adminer/adminer/");
					}
			});
			</script>
			<link rel="stylesheet" type="text/css" href="externals/jush/jush.css" />
			<script type="text/javascript" src="externals/jush/modules/jush.js"></script>
			<script type="text/javascript" src="externals/jush/modules/jush-textarea.js"></script>
			<script type="text/javascript" src="externals/jush/modules/jush-txt.js"></script>
			<script type="text/javascript" src="externals/jush/modules/jush-sql.js"></script>
<?php
		}
	}
	$plugins[] = new MyAdminerModifies();
	//

    /* It is possible to combine customization and plugins:
    class AdminerCustomization extends AdminerPlugin {
    }
    return new AdminerCustomization($plugins);
    */

    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
chdir("./adminer");
include "index.php";
//include "adminer-4.3.0-en.php";
?>