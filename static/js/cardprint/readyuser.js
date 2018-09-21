(function(){

var $$$;
var app = {
	loadUser : function() {
		//alert($$$("cust_id").getValue());
		webix.ajax("/cardprint/getentries?uid="+$$$("cust_id").getValue(), function(text, data){
			data = data.json();
			if(data.error.code==1) {window.location.assign("/cardprint/login/cardprint");}
			if(data.error.code==2) {alert('User '+$$$("cust_id").getValue()+' does not exist')}

			$$$("userlist").parse(data.entries);
			$$$("user_form").parse(data.user);
			//$$$("entriesform").parse(data.balance);
			//app.relations.parse(data.links);
			//app.buildRelations();
		});
		//$$$("userlist").load("/cardprint/cardprint/getentries?uid="+$$$("cust_id").getValue());
	},
	clearForm : function() {
		$$$("id_form").clear();
		$$$("userlist").clearAll();
		$$$("user_form").clear();
		$$$("id_form").focus();
	},
	togglePassVisibility : function(){
		var pass = $$$("pass_field");
		var value = pass.getValue();
		pass.config.type = (pass.config.type == "text")?"password":"text";
		pass.refresh();
		pass.setValue(value);
	},
	saveUserForm : function(){
		$$$("userform").save();
	},
	addUser : function(){
		if ($$$("modes").getValue() == "role")
			return app.addRole();

		var list = $$$("userlist");
		var nid = list.add({
			name:"",
			email:"",
			description:""
		});

		list.select(nid);
		list.showItem(nid);
	},
	deleteUser : function(){
		if ($$$("modes").getValue() == "role")
			return app.deleteRole();

		var list = $$$("userlist");
		var id = list.getCursor();
		if (!id) return;

		webix.confirm({
			text:"Login <strong>\""+list.getItem(id).name+"\"</strong> will be deleted",
			callback:function(mode){
				if (mode){
					list.remove(id);
					$$$("formpanel1").disable();
					app.updateRelations(id);
				}
			}
		});
	},
	toggleView:function(){
		var value = this.getValue();
		$$$(value).show();
	},
	saveRoleForm : function(){
		$$$("roleform").save();
	},
	deleteRole : function(){
		var grid = $$$("rolelist");
		var id = grid.getCursor();
		if (!id) return;

		webix.confirm({
			text:"<strong>\""+grid.getItem(id).name+"\"</strong> rule will be deleted",
			callback:function(mode){
				grid.remove(id);
				$$$("formpanel2").disable();
				app.updateRelations(false, id);
			}
		});
	},
	addRole : function(){
		var nid = $$$("rolelist").add({
			name:"Rule "+webix.uid(),
			description:""
		});

		$$$("rolelist").select(nid);
		$$$("rolelist").showItem(nid);
	},
	addRoleToUser : function(role){
		var user = $$$("userlist").getCursor();
		// if (user > 1000000000)
		// 	return webix.message({
		// 		type:"error",
		// 		text:"User's info contains errors, fix them first."
		// 	});

		// if (role > 1000000000)
		// 	return webix.message({
		// 		type:"error",
		// 		text:"Role's info contains errors, fix them first."
		// 	});

		var id = app.isUserHasRole(user, role);
		if (!id){
			app.relations.add({ user_id:user, role_id:role });
			//$$$("r2ulist").select(role, true);
			$$$("r2ulist").getItem(role).$check = true;
		} else {
			app.relations.remove(id);
			//$$$("r2ulist").unselect(role);
			$$$("r2ulist").getItem(role).$check = false;
		}
		$$$("r2ulist").refresh(role);
	},
	buildRelations:function(){
		var t = app.relationsHash = {};
		app.relations.data.each(function(obj){
			if (!t[obj.user_id])
				t[obj.user_id] = [];
			t[obj.user_id].push(obj.role_id);
		});
	},
	markUsedRoles:function(id){
		var roles = app.relationsHash[id];
		var list = $$$("r2ulist");

		list.data.each(function(item){
			item.$check = false;
		});
		list.refresh();
		if (roles)
			for (var i=0; i<roles.length; i++)
				if (list.exists(roles[i])){
					list.getItem(roles[i]).$check = true;
					list.refresh(roles[i])
				}
	},
	isUserHasRole : function(user, role){
		var exists = false;
		app.relations.data.each(function(obj){
			if (obj.user_id == user && obj.role_id == role)
				exists = obj.id;
		});
		return exists;
	},
	updateRelations:function(user, role){
		var ids = [];
		app.relations.data.each(function(obj){
			if (obj.user_id == user || obj.role_id == role)
				ids.push(obj.id);
		});

		webix.dp(app.relations).ignore(function(){
			for (var i = 0; i < ids.length; i++)
				app.relations.remove(ids[i]);
		});

		app.buildRelations();
	},
	roleTemplate : "#name#<br><small>#description#</small>",
	checkTemplate : '<span class="webix_icon_btn fa-{obj.$check?check-:}square-o" style="max-width:32px;"></span>',
	closeButtonTemplate : "<span class='r2uclose'></span>"
};

app.relations = new webix.DataCollection();

var rolelist = {
	view:"datatable", id:"rolelist", scroll:false,
	columns:[
		{ id:"name", sort:"string", width:200, header:"Name" },
		{ id:"description", header:"Comments", fillspace:true }
	],
	select:"row"
};

var roleform = {
	id:"formpanel2", rows:[
		{ type:"header", template:"Details" },
		{ view:"form", id:"roleform", width:300, elements:[
			{ view:"text", label:"Name", name:"name" },
			{ view:"textarea", label:"Comments", name:"description", height:70 },
			{ view:"button", type: "form", label:"Save details" , click: app.loadUser, inputWidth: 120, align: "center", height: 40 },
			{}
		]}
	]
};

var userlist ={ id:"userlist", view:"datatable", scroll:true,
	columns:[
		{ id:"ent_date", header:"Fecha", width:150, sort:"string", format:webix.i18n.longDateFormatStr ,footer:{text:"Total:"} },
		{ id:"ent_print", header:"Impresiones", css:"number", width:150, sort:"int" ,footer:{ content:"summColumn" } },
		{ id:"ent_copy", header:"Copias", css:"number", width:150, sort:"int", footer:{ content:"summColumn" } },
		{ id:"ent_charge", header:"Recargas", css:"number", sort:"int", fillspace:true ,footer:{ content:"summColumn" } }
	],
	select:"row",
	footer:true,
	on:{
		onBeforeLoad:function(){
			this.clearAll();
			this.showOverlay("Loading...");
		},
		onAfterLoad:function(){
			this.hideOverlay();
		}
	},

};

var userform = {
	type:"line", id:"formpanel1", height:"100%", rows:[
		{ type:"header", css: "webix_header", template:"Usuario CardPrint" },
		{ view:"form", id:"id_form", width:300, elements:[{view:"text", label:"Id", name:"name", id:"cust_id"}] },
		{ view:"accordion", height:"100%", type:"line", rows:[
			{
				header:"Detalles",
				body:{
					view:"form", id:"user_form", width:300, height:"100%", elements:[
						{ view:"text", label:"Nombre", name:"cust_name" },
						{ view:"text", label:"Apellidos", name:"cust_surname" },
						{ view:"text", label:"DNI", name:"cust_dni" },
						{ view:"text", label:"Tel&eacute;fono", name:"cust_telephone" },
						{ view:"textarea", label:"Direcci&oacute;n", name:"cust_address", height:50 },
						{ view:"button",type: "form", label:"Save details" , click: app.loadUser, hotkey: "enter", inputWidth: 120, align: "center", height: 36}
					]
				}
			},
			{
				header:"Acciones",
				body: {
					view:"form", id:"entries_form", width:300, height:"100%", elements:[
						{ view:"text", label:"Email", name:"email" },
						{ view:"button",type: "form", label:"Save details" , click: app.loadUser, hotkey: "enter", inputWidth: 120, align: "center", height: 36}
					]
				}
			}]
		}
	]
};

var userview = { id:"user", type:"wide", cols:[ userform, userlist] };
var rolesview = { id:"role", type:"wide", cols:[rolelist, roleform ]};

webix.protoUI({
	name:"readyuser",
	defaults:{
		type: "wide",
		rows:[
			{ view:"toolbar", elements:[
				{ view:"segmented", width:160, id:"modes", options:[
					{ id:"user", value:"Users" },
					{ id:"role", value:"Roles" }
				], click: app.toggleView },
				{ },
				{ view:"button", type:"icon", icon:"plus-circle", label:"Add", width:80, inputWidth:63,
					click:app.addUser },
				{ view:"button", type:"icon", icon:"trash-o", label:"Delete", width:80,
					click:app.deleteUser }
			]},
			{ animate: false, cells:[ userview, rolesview ] }
		]
	},
	$init:function(){
		var master = this;
		$$$ = function(name){
			return master.$$(name);
		}

		this.$ready.push(this._on_ui_created);
	},
	_on_ui_created:function(){

		$$$("cust_id").attachEvent("onItemClick", function(id, e, node){
			app.clearForm();
		});



		app.clearForm();

		//webix.ajax(this.config.urls.data, function(text, data){
			//data = data.json();

			//$$$("userlist").parse(data);
			//$$$("rolelist").parse(data.roles);
			//app.relations.parse(data.links);
			//app.buildRelations();

			//var first = $$$("userlist").getFirstId();
			//if (first)
			//	$$$("userlist").select(first);
		//});

		//$$$("userform").bind($$$("userlist"));
		//webix.dp($$$("userlist")).define("url", this.config.urls.users);

		//$$$("roleform").bind($$$("rolelist"));
		//webix.dp($$$("rolelist")).define("url", this.config.urls.roles);

		//webix.dp(app.relations).define("url", this.config.urls.links);

		//$$$("formpanel1").disable();
		//$$$("userlist").attachEvent("onAfterSelect", function(id){
		//	$$$("formpanel1").enable();
		//	app.markUsedRoles(id.row);
		//	webix.delay(function(){
		//		var input = $$$("userform").elements["name"].getInputNode();
		//		input.select();
		//		input.focus();
		//	});
		//});

		//$$$("formpanel2").disable();
		//$$$("rolelist").attachEvent("onAfterSelect", function(id){
		//	$$$("formpanel2").enable();
		//	webix.delay(function(){
		//		var input = $$$("roleform").elements["name"].getInputNode();
		//		input.select();
		//		input.focus();
		//	});
		//});

		//$$$('r2ulist').sync($$$("rolelist"));
		//$$$('r2ulist').attachEvent("onItemClick", function(id){
		//	app.addRoleToUser(id);
		//	app.buildRelations();
		//});
	}

}, webix.IdSpace, webix.EventSystem, webix.ui.layout);


})();
