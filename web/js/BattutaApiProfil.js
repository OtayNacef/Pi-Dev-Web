
 	//-------------------------------SELECT CASCADING-------------------------//
  	var currentCities=[];
    var funce;
    function hundler(func)
    {
        funce = function () {
            func();
        }
    }
// This is a demo API key that can only be used for a short period of time, and will be unavailable soon. You should rather request your API key (free)  from http://battuta.medunes.net/ 	
var BATTUTA_KEY="00000000000000000000000000000000"
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
		funce();
        $('.selectpicker').selectpicker('refresh');
        $("#country").trigger("change");
	    

  	});
    
    $("#country").on("change",function()
  	{
  	
  		countryCode=$("#country").val();
  		
  		// Populate country select box from battuta API
	    url="https://battuta.medunes.net/api/region/"
	    +countryCode
	    +"/all/?key="+BATTUTA_KEY+"&callback=?";

  		$.getJSON(url,function(regions)
  		{
  			$("#region option").remove();
		    //loop through regions..
		    $.each(regions,function(key,region)
		    {
		        $("<option></option>")
		         				.attr("value",region.region)
		         				.append(region.region)
		                     	.appendTo($("#region"));
		    });
		    // trigger "change" to fire the #state section update process
			funce();
			$('.selectpicker').selectpicker('refresh');
			$("#region").trigger("change");
	    	
	    }); 
	    
  	});
  	$("#region").on("change",function()
  	{
  		
  		// Populate country select box from battuta API
  		countryCode=$("#country").val();
		region=$("#region").val();
	    url="https://battuta.medunes.net/api/city/"
	    +countryCode
	    +"/search/?region="
	    +region
	    +"&key="
	    +BATTUTA_KEY
	    +"&callback=?";
  		
  		$.getJSON(url,function(cities)
  		{
  			currentCities=cities;
        	var i=0;
        	$("#city option").remove();
        
		    //loop through regions..
		    $.each(cities,function(key,city)
		    {
		        $("<option></option>")
		         				.attr("value",i++)
		         				.append(city.city)
		                .appendTo($("#city"));
		    });
		    // trigger "change" to fire the #state section update process
            funce();
            $('.selectpicker').selectpicker('refresh');
	    	
	    }); 
	    
  	});	
   //-------------------------------END OF SELECT CASCADING-------------------------//