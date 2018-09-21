(function(){

var $$$;
var app = {
	loadUser : function() {
		//alert($$$("cust_id").getValue());
		webix.ajax("/cardprint/getentries?uid="+$$$("cust_id").getValue(), function(text, data){
			data = data.json();
			if(data.error.code==1) {window.location.assign("/cardprint/login/cardprint");}
			if(data.error.code==2) {webix.message({type:"error", text:"User "+$$$("cust_id").getValue()+" does not exist"});}

			$$$("userlist").parse(data.entries);
			$$$("user_form").parse(data.user);
			$$$("balance_form").parse(data.balance);
			//app.relations.parse(data.links);
			//app.buildRelations();
		});
		//$$$("userlist").load("/cardprint/cardprint/getentries?uid="+$$$("cust_id").getValue());
	},
	validateInput: function(n) {
		if (n == "" || isNaN(n) || Math.round(n) != n) {
			return false;
		}
		return true;
	},
	addCopy: function() {
		var uinput = $$$("user_input");
		var dinput = $$$("date_input");

		if (!app.validateInput(uinput.getValue())) {
			webix.message({type:"error", text:"&iexcl;Cantidad inv&aacute;lida!" });
			uinput.setValue("");
			return false;
		}

		webix.modalbox({
			text:"&iquest;A&ntilde;adir "+uinput.getValue()+" copias?",
			buttons:["Si", "No"],
			callback: function(result) {
				switch(result) {
					case "0":
						webix.ajax("/cardprint/addcopies?uid="+$$$("cust_id").getValue()+"&n="+uinput.getValue()+"&d="+dinput.getValue(), function(text, data){
							data = data.json();
							switch(parseInt(data.error.code)) {
								case 1:
									window.location.assign("/cardprint/login/cardprint");
								break;
								case 2:
									webix.message({type:"error", text:"&iexcl;Saldo insuficiente!" });
								break;
								default:
									webix.message('A&ntilde;adidas '+uinput.getValue()+' copias');
									app.loadUser();
								break;
							}
						});
					break;
					default:
						webix.message("Acci&oacute;n cancelada");
					break;
				}
				uinput.setValue("");
				dinput.setValue("");
			}
		});
	},
	addPrint: function() {
		var uinput = $$$("user_input");
		var dinput = $$$("date_input");

		if (!app.validateInput(uinput.getValue())) {
			webix.message({type:"error", text:"&iexcl;Cantidad inv&aacute;lida!" });
			uinput.setValue("");
			return false;
		}

		webix.modalbox({
			text:"&iquest;A&ntilde;adir "+uinput.getValue()+" impresiones?",
			buttons:["Si", "No"],
			callback: function(result) {
				switch(result) {
					case "0":
						webix.ajax("/cardprint/addprints?uid="+$$$("cust_id").getValue()+"&n="+uinput.getValue()+"&d="+dinput.getValue(), function(text, data){
							data = data.json();
							switch(parseInt(data.error.code)) {
								case 1:
									window.location.assign("/cardprint/login/cardprint");
								break;
								case 2:
									webix.message({type:"error", text:"&iexcl;Saldo insuficiente!" });
								break;
								default:
									webix.message('A&ntilde;adidas '+uinput.getValue()+' impresiones');
									app.loadUser();
								break;
							}
						});
					break;
					default:
						webix.message("Acci&oacute;n cancelada");
					break;
				}
				uinput.setValue("");
				dinput.setValue("");
			}
		});
	},
	topUp: function() {
		var uinput = $$$("user_input");
		var dinput = $$$("date_input");

		if (!app.validateInput(uinput.getValue())) {
			webix.message({type:"error", text:"&iexcl;Cantidad inv&aacute;lida!" });
			uinput.setValue("");
			return false;
		}

		webix.modalbox({
			text:"&iquest;Cargar bono de "+uinput.getValue()+"?",
			buttons:["Si", "No"],
			callback: function(result) {
				switch(result) {
					case "0":
						webix.ajax("/cardprint/topup?uid="+$$$("cust_id").getValue()+"&n="+uinput.getValue()+"&d="+dinput.getValue(), function(text, data){
							data = data.json();
							switch(parseInt(data.error.code)) {
								case 1:
									window.location.assign("/cardprint/login/cardprint");
								break;
								default:
									webix.message('Cargado bono de '+uinput.getValue());
									app.loadUser();
								break;
							}
						});
					break;
					default:
						webix.message("Acci&oacute;n cancelada");
					break;
				}
				uinput.setValue("");
				dinput.setValue("");
			}
		});
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
	showForm:function(id){
		$$$(id).show();
	},
	confirm:function(message){
		webix.modalbox({
			text:message,
			buttons:["Si", "No"],
			callback: function(result) {
				switch(result){
					case 0: 
						return true;
					break;
					default:
						return false;
					break;
				}
			}   
		});
	},
	error:function(message){
		webix.confirm({
			type:"confirm-error",
			modal:true,
			text:"Error!<br/>"+message
		});
	},
	warning:function(message){
		webix.confirm({
			type:"confirm-warning",
			modal:true,
			text:message
		});
	},
	roleTemplate : "#name#<br><small>#description#</small>",
	checkTemplate : '<span class="webix_icon_btn fa-{obj.$check?check-:}square-o" style="max-width:32px;"></span>',
	closeButtonTemplate : "<span class='r2uclose'></span>"
};

app.relations = new webix.DataCollection();

var loginform = {
	id:"login_form",
	view:"form",
	borderless:true,
	elements: [
		{ view:"text", label:'Login', name:"login" },
		{ view:"text", label:'Email', name:"email" },
		{ view:"button", value: "Submit", click:function(){
			if (this.getParentView().validate()){ //validate form
                webix.message("All is correct");
                this.getTopParentView().hide(); //hide window
            }
			else
				webix.message({ type:"error", text:"Form data is invalid" });
		}}
	],
	rules:{
		"email":webix.rules.isEmail,
		"login":webix.rules.isNotEmpty
	},
	elementsConfig:{
		labelPosition:"top",
	}
};

var entryform = {
	id:"entry_form",
	view:"form",
	borderless:true,
	elements: [
		{ view:"text", label:'Login', name:"login" },
		{ view:"text", label:'Email', name:"email" },
		{ view:"button", value: "Submit", click:function(){
			if (this.getParentView().validate()){ //validate form
                webix.message("All is correct");
                this.getTopParentView().hide(); //hide window
            }
			else
				webix.message({ type:"error", text:"Form data is invalid" });
		}}
	],
	rules:{
		"email":webix.rules.isEmail,
		"login":webix.rules.isNotEmpty
	},
	elementsConfig:{
		labelPosition:"top",
	}
};

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
		{ id:"ent_date", header:"Fecha", width:150, sort:"string",  format:webix.i18n.longDateFormatStr, footer:{text:"Total:"} },
		{ id:"ent_print", header:"Impresiones", css:"number",align:"right", width:150, sort:"int", format:webix.Number.numToStr({groupDelimiter:",",groupSize:3,decimalSize:0}), footer:{ content:"summColumn",css:"number",align:"right" } },
		{ id:"ent_copy", header:"Copias", css:"number",align:"right", width:150, sort:"int", format:webix.Number.numToStr({groupDelimiter:",",groupSize:3,decimalSize:0}), footer:{ content:"summColumn",css:"number",align:"right"} },
		{ id:"ent_charge", header:"Recargas", css:"number",align:"right", sort:"int", fillspace:true, format:webix.Number.numToStr({groupDelimiter:",",groupSize:3,decimalSize:0}), footer:{ content:"summColumn",css:"number",align:"right"} }
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
	type:"line", id:"formpanel1", rows:[
		{ type:"header", css: "webix_header", template:"Usuario CardPrint" },
		{ view:"form", id:"id_form", width:300, elements:[
			{view:"text", placeholder:"Id CardPrint", name:"name", id:"cust_id", height:50},
		]},
		{view:"form", id:"balance_form", width:300, cols:[
				{view:"label", label:"Saldo disponible:"},
				{view:"label", align:"right", css:"balance", name:"balance", id:"balance",format:webix.Number.numToStr({groupDelimiter:",",groupSize:3,decimalSize:0})},
		]},
		{ view:"accordion", height:"100%", type:"line", rows:[
			{
				header:"Detalles",
				body:{
					view:"form", id:"user_form", width:300, height:"100%", elements:[
						{ view:"text", label:"Nombre", name:"cust_name" },
						{ view:"text", label:"Apellidos", name:"cust_surname" },
						{ view:"text", label:"DNI", name:"cust_dni" },
						{ view:"text", label:"Tel&eacute;fono", name:"cust_telephone" },
						{ view:"textarea", label:"Direcci&oacute;n", name:"cust_address", height:40 },
						{ view:"button",type: "form", label:"Save details" , click: app.loadUser, hotkey: "enter", inputWidth: 120, align: "center", height: 36}
					]
				},collapsed:false
			},
			{
				header:"Acciones",
				body: {
					view:"form", id:"entries_form", width:300, height:"100%", elements:[
						{ view:"text", name:"user_input", placeholder:"Cantidad", id:"user_input",height:40 },
						{ view:"datepicker", name:"date_input", date: new Date(), stringResult:true, placeholder: 'Fecha',id:"date_input", height:40 },
						{ view:"button",
                    		width:"100%",
                    		height:60,
                    		value: 'Copias',
                    		click: app.addCopy,
                		},
                		{ view:"button",
                    		width:"100%",
                    		height:60,
                    		type: "form",
                    		value:'Impresi&oacute;n',
                    		click:app.addPrint,
                		},
        				{ view:"button",
                    		width:"100%",
                    		height:60,
                    		type: "danger",
                    		value: 'Recarga',
                    		click: app.topUp,
                		}
					]
				},collapsed:true
			}]
		}
	]
};

var mainview = { id:"main", type:"wide", cols:[ userform, {view:"resizer"}, userlist ]};
var userview = { id:"users", type:"wide", cols:[ rolelist, {view:"resizer"}, roleform ]};
var confview = { id:"conf", type:"wide", cols:[ roleform, {view:"resizer"}, rolelist ]};

webix.protoUI({
	name:"readyuser",
	defaults:{
		type: "wide",
		rows:[
			{ view:"toolbar", elements:[
				{ view:"segmented", width:250, id:"modes", options:[
					{ id:"main", value:"Principal" },
					{ id:"users", value:"Usuarios" },
					{ id:"conf", value:"Configuracion" }
				], click: app.toggleView },
				{ },
				{ view:"button", type:"icon", icon:"plus-circle", label:"Add", width:80, inputWidth:63, click:app.addUser },
				{ view:"button", type:"icon", icon:"trash-o", label:"Delete", width:80, click:app.deleteUser }
			]},
			{ animate: false, cells:[ mainview, userview, confview ] }
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

		app.clearForm()

		var entrymodal = webix.ui({
            view:"window",
            id:"win2",
            width:300,
            position:"center",
            modal:true,
            head:"User's data",
            body:webix.copy(entryform)
        });

        app.confirm("Hola");
        //app.warning("Esto es un warning");
        //app.error("esto es un error");

		//	webix.confirm("Lorem ipsum dolor sit amet, consectetur adipisicing elit", function(result){
		//		webix.confirm({
		//			title: "Title",
		//			ok:"Yes", cancel:"No",
		//
		//			text:"Lorem ipsum dolor sit amet, consectetur adipisicing elit",
		//			callback:function(){
		//				webix.confirm({
		//					type:"confirm-warning",
		//					text:"Warning!<br/>Lorem ipsum dolor sit amet, consectetur adipisicing elit",
		//					callback:function(){
		//						webix.confirm({
		//							type:"confirm-error",
		//							text:"Error!<br/>Lorem ipsum dolor sit amet, consectetur adipisicing elit"
		//						});
		//					}
		//				});
		//			}
		//		});
		//	});
	
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