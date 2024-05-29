import ApexCharts from 'apexcharts';

export class ApexChartModule {
    constructor(selector, options) {
        this.selector = selector;
        this.options = options;
        this.chart = null;
    }

    render() {
        if (this.chart !== null) {
            this.chart.destroy();
        }

        this.chart = new ApexCharts(document.querySelector(this.selector), this.options);
        this.chart.render();
    }

    updateOptions(newOptions) {
        this.options = newOptions;

        if (this.chart !== null) {
            this.chart.updateOptions(this.options);
        }
    }

    destroy() {
        if (this.chart !== null) {
            this.chart.destroy();
            this.chart = null;
        }
    }
}

console.log('This log comes from assets/apexChart.js  🎉');