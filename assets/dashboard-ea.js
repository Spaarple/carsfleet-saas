'use strict';

import './styles/app.css';
import { ApexChartModule } from './apexChart.js';

const options = {
    chart: {
        height: "100%",
        maxWidth: "100%",
        type: "area",
        fontFamily: "Inter, sans-serif",
        dropShadow: {
            enabled: false,
        },
        toolbar: {
            show: true,
        },
        tooltip: {
            enabled: true,
            x: {
                show: false,
            },
        },
        fill: {
            type: "gradient",
            gradient: {
                opacityFrom: 0.55,
                opacityTo: 0,
                shade: "#1C64F2",
                gradientToColors: ["#1C64F2"],
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 6,
        },
    },
    series: [{
        name: "My series",
        data: window.chartData
    }],
};

const chartModule = new ApexChartModule("#area-chart", options);
chartModule.render();

const chartModule2 = new ApexChartModule("#area-chart2", options);
chartModule2.render();

const chartModule3 = new ApexChartModule("#area-chart3", options);
chartModule3.render();