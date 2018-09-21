<?php include(APP_DIR.'views/cardprint/header.php'); ?>
<script>
	webix.i18n.setLocale("es-ES");
	webix.ready(function(){
		var appui = {
			type:"header",
			rows:[
				//{ template:"User Management", css:"myheader", height:30 },
				{ view:"readyuser", id:"user1", urls:{
					//	data:  "debug->dummy",
					//	users: "debug->dummy",
					//	roles: "debug->dummy",
					//	links: "debug->dummy"
					}
				}
			]
		};
		//webix.ui({ rows:[ {view:"navbar", value:"user"}, appui]});
		webix.ui({ rows:[appui]});
	});
</script>

<?php include(APP_DIR.'views/cardprint/footer.php'); ?>