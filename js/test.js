$(document).ready(function(){
	$.ajax({
		url: 'https://www.gokgs.com/json-cors/access',
		type: "POST",
		dataType: 'json',
		crossDomain: true,
		headers: {
              "accept": "application/json",
              "Access-Control-Allow-Origin":"*"
        },
		data: JSON.stringify({
		  "type": "LOGIN",
		  "name": "samgk",
		  "password": "raku4k",
		  "locale": "en_US"
		}),
	});
});