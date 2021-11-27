type = ['','info','success','warning','danger'];
    	

demo = {
    initPickColor: function(){
        $('.pick-class-label').click(function(){
            var new_class = $(this).attr('new-class');  
            var old_class = $('#display-buttons').attr('data-class');
            var display_div = $('#display-buttons');
            if(display_div.length) {
            var display_buttons = display_div.find('.btn');
            display_buttons.removeClass(old_class);
            display_buttons.addClass(new_class);
            display_div.attr('data-class', new_class);
            }
        });
    },
    
    initChartist: function(){    
        
        var dataSales = {
          labels: ['9:00AM', '12:00AM', '3:00PM', '6:00PM', '9:00PM', '12:00PM', '3:00AM', '6:00AM'],
          series: [
             [287, 385, 490, 492, 554, 586, 698, 695, 752, 788, 846, 944],
            [67, 152, 143, 240, 287, 335, 435, 437, 539, 542, 544, 647],
            [23, 113, 67, 108, 190, 239, 307, 308, 439, 410, 410, 509]
          ]
        };
        
        var optionsSales = {
          lineSmooth: false,
          low: 0,
          high: 800,
          showArea: true,
          height: "245px",
          axisX: {
            showGrid: false,
          },
          lineSmooth: Chartist.Interpolation.simple({
            divisor: 3
          }),
          showLine: false,
          showPoint: false,
        };
        
        var responsiveSales = [
          ['screen and (max-width: 640px)', {
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];
    
        Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);
        
    
        var data = {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          series: [
            [542, 443, 320, 780, 553, 453, 326, 434, 568, 610, 756, 895],
            [412, 243, 280, 580, 453, 353, 300, 364, 368, 410, 636, 695]
          ]
        };
        
        var options = {
            seriesBarDistance: 10,
            axisX: {
                showGrid: false
            },
            height: "245px"
        };
        
        var responsiveOptions = [
          ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
              labelInterpolationFnc: function (value) {
                return value[0];
              }
            }
          }]
        ];
        
        Chartist.Bar('#chartActivity', data, options, responsiveOptions);
    
        var dataPreferences = {
            series: [
                [25, 30, 20, 25]
            ]
        };
        
        var optionsPreferences = {
            donut: true,
            donutWidth: 40,
            startAngle: 0,
            total: 100,
            showLabel: false,
            axisX: {
                showGrid: false
            }
        };
    
        Chartist.Pie('#chartPreferences', dataPreferences, optionsPreferences);
        
        Chartist.Pie('#chartPreferences', {
          labels: ['62%','32%','6%'],
          series: [62, 32, 6]
        });   
    },
    
    initGoogleMaps: function(){
        var myLatlng = new google.maps.LatLng(40.748817, -73.985428);
        var mapOptions = {
          zoom: 13,
          center: myLatlng,
          scrollwheel: false, //we disable de scroll over the map, it is a really annoing when you scroll through page
          styles: [{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]
    
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        
        var marker = new google.maps.Marker({
            position: myLatlng,
            title:"Hello World!"
        });
        
        // To add the marker to the map, call setMap();
        marker.setMap(map);
    },
        
}


 
//POTENZA var
var POTENZA = {};
 
 (function($){
  "use strict";


/*************************
      Predefined Variables
*************************/ 
var $window = $(window),
  $document = $(document),
  $body = $('body');

//Check if function exists
$.fn.exists = function () {
  return this.length > 0;
};



  /*************************
       owl-carousel 
*************************/

 POTENZA.carousel = function () {
    $(".owl-carousel").each(function () {
        var $this = $(this),
            $items = ($this.data('items')) ? $this.data('items') : 1,
            $loop = ($this.data('loop')) ? $this.data('loop') : true,
            $navdots = ($this.data('nav-dots')) ? $this.data('nav-dots') : false,
            $navarrow = ($this.data('nav-arrow')) ? $this.data('nav-arrow') : false,
            $autoplay = ($this.attr('data-autoplay')) ? $this.data('autoplay') : true,
            $space = ($this.attr('data-space')) ? $this.data('space') : 30;     
            $(this).owlCarousel({
                loop: $loop,
                items: $items,
                responsive: {
                  0:{items: $this.data('xx-items') ? $this.data('xx-items') : 1},
                  480:{items: $this.data('xs-items') ? $this.data('xs-items') : 1},
                  768:{items: $this.data('sm-items') ? $this.data('sm-items') : 2},
                  980:{items: $this.data('md-items') ? $this.data('md-items') : 3},
                  1200:{items: $items}
                },
                dots: $navdots,
                margin:$space,
				singleItem:true,
                nav: $navarrow,
                navText:["<i class='fa fa-angle-left fa-2x'></i>","<i class='fa fa-angle-right fa-2x'></i>"],
                autoplay: $autoplay,
                autoplayHoverPause: true   
            }); 
           
    }); 
}



 //Document ready functions
    $document.ready(function () {
        POTENZA.carousel();
    });


})(jQuery);

$( ".sortable" ).sortable();
$( ".sortable" ).sortable({
  update: function( event, ui ) {
                var strItems = "";
                $("#sortable").children().each(function (i) {
                    var li = $(this);
                    strItems += li.attr("id")+",";
                });
                var strItems = strItems.substring(0, strItems.length-1);
                $("#datepref").val(strItems);
  }
});

$("#ex2").slider({});
$("#ex3").slider({});
$(document).ready(function(){
    $(".tooltip-min,.tooltip-max").css("display","none");
    $(".tooltip-max").find('.bottom').removeClass("bottom");
    $(".tooltip-max").addClass("top");
    $("#ex2").change(function(){
        var age=$(this).val();
        var slit=age.split(",");
        $( "#slider1" ).val( slit[0]);
        $( "#slider2" ).val(slit[1]);
    });
    $("#ex3").change(function(){
        var age=$(this).val();
        var slit=age.split(",");
        $( "#slider3" ).val( slit[0]);
        $( "#slider4" ).val(slit[1]);
    });
    $(".dropdown-menu.inner").css("overflow-y","scroll");
});

