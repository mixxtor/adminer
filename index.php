<?php
define("CURRENT_DIR", __DIR__."/");
if (file_exists("adminer.php"))
{
	define("ADMINER_WEB_PATH", "");
	define("ADMINER_DIR", "");
}
else
{
	define("ADMINER_WEB_PATH", "adminer/");
	define("ADMINER_DIR", CURRENT_DIR.ADMINER_WEB_PATH);
}

function adminer_object()
{
    // required to run any plugin
    include_once CURRENT_DIR."plugins/plugin.php";

    // autoloader
    foreach (glob(CURRENT_DIR."plugins/*.php") as $filename) {
        include_once $filename;
    }

    $plugins = array(
        // specify enabled plugins here
        new AdminerFileUpload("data/"),
        new AdminerSlugify,
        new AdminerForeignSystem,
    );

	$_php_my_admin_plugins = array();
	include_once (CURRENT_DIR."plugins/ux/_php_my_admin_plugins.php");
	$plugins += $_php_my_admin_plugins;

	// my
	class MyAdminerModifies
	{
		function head()
		{
?>
			<link rel="shortcut icon" type="image/x-icon" href="<?=ADMINER_WEB_PATH?>static/favicon.ico">
			<link rel="apple-touch-icon" href="<?=ADMINER_WEB_PATH?>static/favicon.ico">

			<link rel="stylesheet" type="text/css" href="<?=ADMINER_WEB_PATH?>static/default.css" />

<?php if (file_exists(CURRENT_DIR."adminer.css")) { ?>
			<link rel="stylesheet" type="text/css" href="adminer.css" />
<?php } else if (file_exists(CURRENT_DIR."designs/nette-mod/adminer.css")) { /* default skin */ ?>
			<link rel="stylesheet" type="text/css" href="designs/nette-mod/adminer.css" />
<?php } ?>

			<script type="text/javascript" src="<?=ADMINER_WEB_PATH?>static/functions.js"></script>
			<script type="text/javascript" src="<?=ADMINER_WEB_PATH?>static/editing.js"></script>
			<script>
			document.addEventListener("DOMContentLoaded", function(event)
			{
				var inputs = document.getElementsByTagName("INPUT");
				var i, cnt = inputs.length;
				for (i=0; i<cnt; i++)
					if (inputs[i].type == "image")
					{
						inputs[i].src = inputs[i].getAttribute("src").replace("../adminer/", "<?=ADMINER_WEB_PATH?>");
						console.log(inputs[i].src);
					}
			});
			</script>
<?php if (file_exists("externals/jush")) { ?>
			<link rel="stylesheet" type="text/css" href="externals/jush/jush.css" />
			<script type="text/javascript" src="externals/jush/modules/jush.js"></script>
			<script type="text/javascript" src="externals/jush/modules/jush-textarea.js"></script>
			<script type="text/javascript" src="externals/jush/modules/jush-txt.js"></script>
			<script type="text/javascript" src="externals/jush/modules/jush-sql.js"></script>
<?php } ?>
<?php
			return false;		// do not use default adminer.css + few other files
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
if (ADMINER_DIR != "")
{
	chdir(ADMINER_DIR);
	include "index.php";
}
else
	include "adminer.php";
?>