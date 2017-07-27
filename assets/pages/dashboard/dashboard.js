$( document ).ready( function(){

for ( var d in datasets ) {

    var index = "myChart"+parseInt(d);
    new Morris.Bar({

        // ID of the element in which to draw the chart.
        element: index,
        
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: datasets[d],

        xkey: 'label',
        ykeys: ['value'],
        labels: ['Pontos'],
    });
    
}
});
