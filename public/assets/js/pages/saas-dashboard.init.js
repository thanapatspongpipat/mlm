!function(){var e={series:[{name:"series1",data:[31,40,36,51,49,72,69,56,68,82,68,76]}],chart:{height:320,type:"line",toolbar:"false",dropShadow:{enabled:!0,color:"#000",top:18,left:7,blur:8,opacity:.2}},dataLabels:{enabled:!1},colors:["#556ee6"],stroke:{curve:"smooth",width:3}};new ApexCharts(document.querySelector("#line-chart"),e).render();e={series:[56,38,26],chart:{type:"donut",height:262},labels:["Series A","Series B","Series C"],colors:["#556ee6","#34c38f","#f46a6a"],legend:{show:!1},plotOptions:{pie:{donut:{size:"70%"}}}};new ApexCharts(document.querySelector("#donut-chart"),e).render();new ApexCharts(document.querySelector("#radialchart-1"),{series:[37],chart:{type:"radialBar",width:60,height:60,sparkline:{enabled:!0}},dataLabels:{enabled:!1},colors:["#556ee6"],plotOptions:{radialBar:{hollow:{margin:0,size:"60%"},track:{margin:0},dataLabels:{show:!1}}}}).render();new ApexCharts(document.querySelector("#radialchart-2"),{series:[72],chart:{type:"radialBar",width:60,height:60,sparkline:{enabled:!0}},dataLabels:{enabled:!1},colors:["#34c38f"],plotOptions:{radialBar:{hollow:{margin:0,size:"60%"},track:{margin:0},dataLabels:{show:!1}}}}).render();new ApexCharts(document.querySelector("#radialchart-3"),{series:[54],chart:{type:"radialBar",width:60,height:60,sparkline:{enabled:!0}},dataLabels:{enabled:!1},colors:["#f46a6a"],plotOptions:{radialBar:{hollow:{margin:0,size:"60%"},track:{margin:0},dataLabels:{show:!1}}}}).render()}();