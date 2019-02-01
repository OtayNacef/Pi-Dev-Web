
 	//-------------------------------SELECT CASCADING-------------------------//
  	var currentCities=[];
// This is a demo API key that can only be used for a short period of time, and will be unavailable soon. You should rather request your API key (free)  from http://battuta.medunes.net/ 	
var BATTUTA_KEY="b55b50cebe8809ff19058499250142fe"

	function vitEn(country) {
        var url1="http://battuta.medunes.net/api/country/code/"+country+"/?key="+BATTUTA_KEY+"&callback=?";
		var pays = "";

		$.ajax({
			url:url1,
			type:"get",
            dataType: "json",
            async: false,
			success:function (c) {
				pays = c[0].name;
            },
            complete:function () {
				return pays;
            }
		});
    }
  	// Populate country select box from battuta API
	url="https://battuta.medunes.net/api/country/all/?key="+BATTUTA_KEY+"&callback=?";
  	$.getJSON(url,function(countries)
  	{
	    //loop through countries..
	    $.each(countries,function(key,country)
	    {
	        $("<option></option>")
	         				.attr("value",country.code)
	         				.append(country.name)
	                     	.appendTo($("#country"));
	       
	    }); 
	    // trigger "change" to fire the #state section update process
	    

  	});
    
    $("#country").on("change",function()
  	{
		$("#region option").remove();
  		countryCode=$("#country").val();
		for(i=0;i<countryCode.length;i++)
		{
			// Populate country select box from battuta API
			url="https://battuta.medunes.net/api/region/"+countryCode[i]+"/all/?key="+BATTUTA_KEY+"&callback=?";
			$.getJSON(url,function(regions)
			{
				//loop through regions..
				$.each(regions,function(key,region)
				{
					$("<option></option>")
									.attr("value",region.region)
									.append(region.region)
									.appendTo($("#region"));
				});
				// trigger "change" to fire the #state section update process
				
			});
	    }
		});
  	$("#region").on("change",function()
  	{
		$("#city option").remove();
  		// Populate country select box from battuta API
  		countryCode=$("#country").val();
		for(i=0;i<countryCode.length;i++)
		{
			region=$("#region").val();
			for(j=0;j<region.length;j++)
			{
				url="https://battuta.medunes.net/api/city/"+countryCode[i]+"/search/?region="+region[j]+"&key="+BATTUTA_KEY+"&callback=?";
				$.getJSON(url,function(cities)
				{
					var i=0;	
					//loop through regions..
					$.each(cities,function(key,city)
					{
						$("<option></option>")
										.attr("value",i++)
										.append(city.city)
								.appendTo($("#city"));
					});
					// trigger "change" to fire the #state section update process
					
				});
			}
		}  
  	});
   //-------------------------------END OF SELECT CASCADING-------------------------//