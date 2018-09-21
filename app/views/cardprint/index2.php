<?php include(APP_DIR.'views/cardprint/header.php'); ?>

<script type="text/javascript">

	var cities = [
		{id:1, value:"Berlin"}, {id:2, value:"Kiev"}, {id:3, value:"Minsk"},
		{id:4, value:"Moscow"}, {id:5, value:"Prague"}, {id:6, value:"Riga"},
		{id:7, value:"St.Petersburg"}, {id:8, value:"Tallin"}, {id:9, value:"Vilnius"},{id:10, value:"Warsaw"}
	];

	var offers = [
		{id:1, direction:"<b>Tallin</b> EE - <b>Berlin</b> Tegel DE", date:new Date(2013,7,25), price:"450", save:"45", places:21},
		{id:2, direction:"<b>Moscow</b> Vnukovo RU - <b>Kiev</b> Borispol UA", date: new Date(2013,7,28), price:"160", save:"65", places:5},
		{id:3, direction:"<b>Riga</b> International LV - <b>Warsaw</b> Modlin", date: new Date(2013,7,16), price:"220", save:"110", places:2},
		{id:4, direction:"<b>Vilnius</b> LT - <b>Kiev</b> Zhulhany UA", date: new Date(2013,8,1), price:"140", save:"40", places:35},
		{id:5, direction:"<b>Minsk</b> International 2 BY- <b>Berlin</b> Schoenefeld DE", date: new Date(2013,8,6), price:"378", save:"35", places:25},
		{id:6, direction:"<b>St. Petersburg</b> Pulkovo - <b>Tallin</b> Estonia", date: new Date(2013,7,31), price:"90", save:"82", places:11},
		{id:7, direction:"<b>Kiev</b> Zhulhany UA - <b>Moscow</b> Vnukovo RU", date: new Date(2013,8,15), price:"220", save:"30", places:41},
		{id:8, direction:"<b>Moscow</b> Sheremetyevo RU - <b>Vilnius</b> LT", date: new Date(2013,8,11), price:"321", save:"44", places:32},
		{id:9, direction:"<b>Warsaw</b> PL - <b>Minsk</b> International 2 BY", date: new Date(2013,8,5), price:"256", save:"32", places:55},
		{id:10, direction:"<b>Prague</b> CZ - <b>St. Petersburg</b> Pulkovo", date: new Date(2013,7,30), price:"311", save:"63", places:15},
	];

	var flight_selector = {
		width: 360,
		multi:false, rows:[
			{header:"Book Flight", body:{
				view:"form", elements:[
					{view:"radio", id:"radio1", value:1, options:[{id:1, value:"One-Way"}, {id:2, value:"Return"}], label:"Trip"},
					{view:"combo", label:"From", options:cities, placeholder:"Select departure point"},
					{view:"combo", label:"To", options:cities, placeholder:"Select destination"},
					{view:"datepicker", label:"Departure Date", value:new Date(), format:"%d  %M %Y"},
					{view:"datepicker", id:"datepicker2", label:"Return Date", value:new Date(), format:"%d  %M %Y", hidden:true},
					{view:"checkbox", id:"flexible", value:0, label: "Flexible dates"},
					{
						cols:[
							{view:"label",  value: "Passengers", width: 100},
							{view:"counter",  labelPosition: "top", label:"Adults", value:1, min:1},
							{view:"counter",  labelPosition: "top", label:"Children"}
						]
					},
					{ height: 10},
					{view:"button", type:"form", value:"Book Now", inputWidth:140, align: "center"}, {}

				],
				elementsConfig:{
					labelWidth:100, labelAlign:"left"
				}
			}},
			{header:"Register", collapsed:true, body:{
				view:"form", elements:[
					{view:"text", label:"First Name", placeholder:"Matthew"},
					{view:"text", label:"Last Name", placeholder:"Clark"},
					{view:"text", label:"Email", placeholder:"mattclark@some.com"},
					{view:"text", label:"Login", labelWidth:120, placeholder:"Matt"},
					{view:"text", label:"Password", type:"password", labelWidth:120, placeholder:"********"},
					{view:"text", label:"Confirm Password", type:"password", labelWidth:120, placeholder:"********"},
					{view:"button", value:"Register", type:"form", inputWidth:100, align:"center"}, {}
				],
				elementsConfig:{labelAlign:"left" }
			}}
		]
	};

	var special_offers = {
		gravity:3, rows:[
			{type:"header", css: "webix_header rounded_top", template:"Special offers"},
			{
				view: "datatable", select:true,
				columns:[
					{id:"id", header:"#", width:40},
					{id:"direction", header:"Direction", minWidth:320, fillspace:true },
					{id:"date", header:"Date", width:150, sort:"date", format:webix.i18n.longDateFormatStr},
					{id:"price", header:"Price", css:"number", width:95, sort:"int", format:webix.i18n.priceFormat},
					{id:"save", header:"You save", css:"number", width:95, sort:"int", format:webix.i18n.priceFormat},
					{id:"places", header:"Tickets", css:"number", width:65, sort:"int"},
					{id:"book", header:"Booking", css:"webix_el_button", width:100, template:"<a href='javascript:void(0)' class='check_flight'>Book now</a>"}
				],
				data:offers,
				onClick:{
					"check_flight":function(){
						return false;
					}
				},
				ready:function(){
					this.select("3");
				}
			}
		]
	};

	var lang = {
		view:"popup", id:"lang",
		head:false, width: 100,
		body:{
			view:"list", scroll:false,
			yCount:4, select:true, borderless:true,
			template:"#lang#",
			data:[
				{id:1, lang:"English"},
				{id:2, lang:"French"},
				{id:3, lang:"German"},
				{id:4, lang:"Russian"}
			],
			on:{"onAfterSelect":function(){
				$$("lang").hide();
			}}
		}
	};

	var ui = {

		rows:[
			{
				type: "space",
				rows:[	{ view:"toolbar", height: 45,elements:[
					{view:"label", template: "<div style='font-size:18px;line-height:40px; margin-left:-4px;'>Card Print</div>"},{},
					{view:"icon", icon:"user"},
					{view:"icon", icon:"calendar"},
					{view:"icon", icon:"cog", popup:"lang"}

				]},
				{autoheight:true, type: "wide", cols:[special_offers, flight_selector]}]
			}
		]
	};


	webix.ready(function(){

		webix.ui(ui);
		webix.ui(lang);

		$$("radio1").attachEvent("onChange", function(newv, oldv){
			if(newv == 2)
				$$("datepicker2").show();
			else
				$$("datepicker2").hide();
		});
	});

</script>

<?php include(APP_DIR.'views/cardprint/footer.php'); ?>