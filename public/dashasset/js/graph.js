// Dashboard graph 1
var salesDataInMinutes = [
    4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,
];

var options = {
    series: [
        {
            name: "Sales",
            data: salesDataInMinutes,
        },
    ],
    chart: {
        height: 350,
        type: "line",
        zoom: {
            enabled: false,
        },
        toolbar: {
            show: false,
        },
    },
    stroke: {
        width: 5,
        curve: "smooth",
        colors: ["#000"], // Set the line color to black (#000000)
    },
    xaxis: {
        type: "datetime",
        categories: [
            "1/11/2000",
            "2/11/2000",
            "3/11/2000",
            "4/11/2000",
            "5/11/2000",
            "6/11/2000",
            "7/11/2000",
            "8/11/2000",
            "9/11/2000",
            "10/11/2000",
            "11/11/2000",
            "12/11/2000",
            "1/11/2001",
            "2/11/2001",
            "3/11/2001",
            "4/11/2001",
            "5/11/2001",
            "6/11/2001",
        ],
        tickAmount: 10,
        labels: {
            formatter: function (value, timestamp, opts) {
                return opts.dateFormatter(new Date(timestamp), "dd MMM");
            },
        },
    },
    yaxis: {
        labels: {
            formatter: function (value) {
                // Convert the value to minutes
                var minutes = Math.floor(value);
                return minutes + " min";
            },
        },
        // title: {
        //   text: "Time (minutes)",
        //   style: {
        //     fontSize: "16px",
        //     color: "#666",
        //   },
        // },
    },
    // title: {
    //     text: "147 min",
    //     align: "center",
    //     style: {
    //         fontSize: "24px",
    //         color: "#666",
    //     },
    // },
    // fill: {
    //   type: 'solid',
    //   color: '#9E1F63',
    //   opacity: 0.8,
    // },
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();
// End dashboard graph 1

// Dashboard graph 2
var salesDataInMinutes = [
    4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5,
];

var options = {
    series: [
        {
            name: "Sales",
            data: salesDataInMinutes,
        },
    ],
    chart: {
        height: 350,
        type: "line",
        zoom: {
            enabled: false,
        },
        toolbar: {
            show: false,
        },
    },
    stroke: {
        width: 5,
        curve: "smooth",
        colors: ["#000"], // Set the line color to black (#000000)
    },
    xaxis: {
        type: "datetime",
        categories: [
            "1/11/2000",
            "2/11/2000",
            "3/11/2000",
            "4/11/2000",
            "5/11/2000",
            "6/11/2000",
            "7/11/2000",
            "8/11/2000",
            "9/11/2000",
            "10/11/2000",
            "11/11/2000",
            "12/11/2000",
            "1/11/2001",
            "2/11/2001",
            "3/11/2001",
            "4/11/2001",
            "5/11/2001",
            "6/11/2001",
        ],
        tickAmount: 10,
        labels: {
            formatter: function (value, timestamp, opts) {
                return opts.dateFormatter(new Date(timestamp), "dd MMM");
            },
        },
    },
    yaxis: {
        labels: {
            formatter: function (value) {
                // Convert the value to minutes
                var minutes = Math.floor(value);
                return minutes + " min";
            },
        },
    },
};

var chart = new ApexCharts(document.querySelector("#chart2"), options);
chart.render();
// End dashboard graph 2