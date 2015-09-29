(function(){
	
	var app = angular.module("calc",[]);
	
	app.controller("CalcController",function(){
		
		this.offer = banks;
	});
	
	// Create array of Banks
	var banks = [
		{
			alias: "slsp",
			name: "Slovenská sporiteľňa",
			interestRate: 2.1,
			conditions: ["účet v banke","sporiaci účet"]
		},
		
		{
			alias: "vub",
			name: "VÚB",
			interestRate: 2.3,
			conditions: ["účet v banke"]
		},
		
		{
			alias: "uni",
			name: "Unicredit Bank",
			interestRate: 2.4,
			conditions: ["podmienka 1","podmienka 2","podmienka 3"]
		},
		
		{
			alias: "sber",
			name: "SBER Bank",
			interestRate: 2,
			conditions: ["účet v banke"]
		}
	]
	

	
})();


$(document).ready(function(){
	
	tableAccordion();
	
	createTotalAmountSlider();
	createTotalDurationSlider();
	
	createSampleGraph();
	
	formatThousands();
	
});


// Accordion function - detailed bank offer information
var tableAccordion = function(){
	var trigger = $(".accordionTrigger");
	var content = $(".accordionContent");
	
	trigger.click(function(){
		$(this).toggleClass("active");
		$(this).siblings(content).toggleClass("active");
	});
};

// Create the graph
var createSampleGraph = function(){
	
	var interestPaymentsDataset = [];
	for(i=0;i<=30;i++){
		interestPaymentsDataset.push((Math.round((1458.60-41.7*i-0.5*i)*100)/100).toFixed(2));
	}
	
	var principalPaymentsDataset = [];
	for(i=0;i<=30;i++){
		principalPaymentsDataset.push((Math.round((2677.08+41.7*i+0.5*i)*100)/100).toFixed(2));	
	}
	
	var totalYearlyPaymentsDataset = [];
	for(i=0;i<=30;i++){
		totalYearlyPaymentsDataset.push(4145.69);
	}
	
	var countYears = [];
	for(i=0;i<=30;i++){
		countYears.push(i);
	}
	
	
	var data = {
		// A labels array that can contain any sort of values
		labels: countYears,
		// Our series array that contains series objects or in this case series data arrays
		series: [{
			name: 'Ročná splátka spolu',
			data: totalYearlyPaymentsDataset
		}, {
			name: 'Ročná splátka istiny',
			data: principalPaymentsDataset 
		}, {
			name: 'Ročná splátka úroku',
			data: interestPaymentsDataset
		}]
	};
	
	var options = {
		high: 4250,
		low: 0,
		showArea: true,
		fullWidth: true,
		chartPadding: {
			right: 30
		},
		axisX: {
			labelInterpolationFnc: function(value, index) {
				return index % 5 === 0 ? value : null;
			}
		},
		series: {
			'Ročná splátka': {
				lineSmooth: Chartist.Interpolation.step(),
				showArea: true
			},
			'Ročná splátka istiny': {
				lineSmooth: Chartist.Interpolation.simple(),
				showArea: true
			},
			'Ročná splátka úroku': {
				showArea: true
			}
		}
	};
	


	// Create a new line chart object where as first parameter we pass in a selector
	// that is resolving to our chart container element. The Second parameter
	// is the actual data object.
	new Chartist.Line('.ct-chart', data, options);
	
	var chart = $('.ct-chart');

	var toolTip = chart
	  .append('<div class="tooltipCustom"></div>')
	  .find('.tooltipCustom')
	  .hide();

	chart.on('mouseenter', '.ct-point', function() {
		var point = $(this),
		value = point.attr('ct:value'),
		seriesName = point.parent().attr('ct:series-name');
		toolTip.html(seriesName + '<br>' + value).show();
	});

	chart.on('mouseleave', '.ct-point', function() {
	  toolTip.hide();
	});

	chart.on('mousemove', function(event) {
		toolTip.css({
			left: (event.offsetX || event.originalEvent.layerX) - toolTip.width() / 2 - 10,
			top: (event.offsetY || event.originalEvent.layerY) - toolTip.height() - 40
		});
	});

	
};

// Function to add a thousand separator
var numberWithCommas = function(x){
	var parts = x.toString().split(".");
	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	return parts.join(".");
}
var formatThousands = function(){
	var target = $(".ui-slider-label");
	target.each(function(){
		var originalValue = $(this).html();
		var newValue = numberWithCommas(originalValue);
		$(this).html(newValue);
	});
};


var createTotalAmountSlider = function(){
	// Create the array of values
	var totalAmountArray = [ ];
	for(i = 0; i < 201; i++) { 
		totalAmountArray.push((i*1000).toString());
	}
	for(i=200; i<351; i=i+5){
		totalAmountArray.push((i*1000).toString());
	}
	for(i=350; i<501; i=i+10){
		totalAmountArray.push((i*1000).toString());
	}
	// Create the slider
	$("#totalAmountSlider").slider({
	    range: "min",
		value: 0,
		min: 0,
		max: (totalAmountArray.length)-1,
		step: 1,
		slide: function(event, ui) {
			$("#totalAmount").val(totalAmountArray[ui.value]);
		},
		change: function( event, ui ) {
			$("#totalAmount").val(totalAmountArray[ui.value]);
		}
	}).slider("pips",{
		rest: "label",
        labels: totalAmountArray,
		step: 25
	});
	// Update the slider when the input value gets updated
	$( "#totalAmount" ).change(function() {
		$("#totalAmountSlider").slider( "value", this.value/1000 );
    });
};


var createTotalDurationSlider = function(){
	// Create the array of values
	var totalDurationArray = [ ];
	for(i = 0; i < 31; i++) { 
		totalDurationArray.push(i.toString());
	}
	// Create the slider
	$("#totalDurationSlider").slider({
	    range: "min",
		value: 0,
		min: 0,
		max: (totalDurationArray.length)-1,
		step: 1,
		slide: function(event, ui) {
			$("#totalDuration").val(totalDurationArray[ui.value]);
		},
		change: function( event, ui ) {
			$("#totalDuration").val(totalDurationArray[ui.value]);
		}
	}).slider("pips",{
		rest: "label",
        labels: totalDurationArray,
		step: 5
	});
	// Update the slider when the input value gets changed
	$( "#totalDuration" ).change(function() {
      $("#totalDurationSlider").slider( "value", this.value );
    });
};


