'use strict';

import './styles/app.css';
import { ApexChartModule } from './apexChart.js';

const baseOptions = {
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
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 6,
        },
    }
};

const optionsBorrowChart = {
    ...baseOptions,
    series: [{
        name: "Emprunts",
        data: Object.values(window.borrowChart)
    }],
    xaxis: {
        categories: Object.keys(window.borrowChart)
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
};
const chartModule = new ApexChartModule("#borrow-chart", optionsBorrowChart);
chartModule.render();

const optionsAccidentChart = {
    ...baseOptions,
    series: [{
        name: "Accidents",
        data: Object.values(window.accidentChart),
        color: "#E63946"
    }],
    xaxis: {
        categories: Object.keys(window.accidentChart)
    },
    fill: {
        type: "gradient",
        gradient: {
            opacityFrom: 0.55,
            opacityTo: 0,
            shade: "#E63946",
            gradientToColors: ["#E63946"],
        },
    },
};
const chartModule2 = new ApexChartModule("#accident-chart", optionsAccidentChart);
chartModule2.render();