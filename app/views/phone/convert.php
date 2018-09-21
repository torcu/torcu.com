<html>
	<!-- script src="<?php echo BASE_URL; ?>static/js/jquery.js"></script -->
    <script src="<?php echo BASE_URL; ?>static/js/PhoneNumber/PhoneNumberMetadata.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>static/js/PhoneNumber/PhoneNumberNormalizer.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>static/js/PhoneNumber/PhoneNumber.js" type="text/javascript"></script>

	<script type="text/javascript">
	
		function parse_number(num, userCountry) {
			if(!isNaN(num) && num.length <= 5) {
				return {"error":false,"internationalNumber":num};
			}
			parsed = PhoneNumber.Parse(num, userCountry);
			parsed.error = false;

			if(parsed.internationalNumber == null) {
				 parsed = {"error":true,"internationalNumber":num};
			} 
			console.log(parsed);
			return parsed;
		}

		function clean_number(num) {
			return num.replace(/\+/g, cc_settings['/DialingOptions/LongDistPrefix']).replace(/ |-|\.|_|\(|\)/g,'');
		}
		
	//	var getUrlParameter = function getUrlParameter(sParam) {
	//		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	//			sURLVariables = sPageURL.split('&'),
	//			sParameterName,
	//			i;
    //
	//		for (i = 0; i < sURLVariables.length; i++) {
	//			sParameterName = sURLVariables[i].split('=');
    //
	//			if (sParameterName[0] === sParam) {
	//				return sParameterName[1] === undefined ? true : sParameterName[1];
	//			}
	//		}
	//	};
	//	
	//	var n = getUrlParameter('n');
	//	var r = getUrlParameter('r');
		
		parsed = parse_number('<?php echo $phone ?>','<?php echo $region ?>');
		if(!parsed.error) {
			document.write(parsed.internationalNumber);
		}
		
	</script>
</head>
<body></body>
</html>
	

