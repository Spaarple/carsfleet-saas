import AirDatepicker from 'air-datepicker';
import LocalFr from 'air-datepicker/locale/fr';

export class Calendar {

    constructor(selector, options = {}) {
        this.selector = selector;

        const item = document.querySelector(selector);

        this.options = {
            view: item.dataset.viewCalendar,
            dateFormat: item.dataset.dateFormat,
            selectedDates: Date.parse(item.dataset.selectedDates),
            inline: item.dataset.viewInline,
            minDate: Date.parse(item.dataset.minDate),
            maxDate: Date.parse(item.dataset.maxDate),
            range: item.dataset.range,
            multipleDatesSeparator: ' - ',
            timepicker: item.dataset.timepicker,
            minView: 'months',

            locale: LocalFr,
            prevHtml: '<svg xmlns="http://www.w3.org/2000/svg" ' +
                'fill="none" ' +
                'viewBox="0 0 24 24" ' +
                'stroke-width="1.5" ' +
                'stroke="currentColor" ' +
                'class="size-6">' +
                '  <path stroke-linecap="round" ' +
                'stroke-linejoin="round" d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />' +
                '</svg>',
            nextHtml: '<svg xmlns="http://www.w3.org/2000/svg" ' +
                'fill="none" viewBox="0 0 24 24" ' +
                'stroke-width="1.5" ' +
                'stroke="currentColor" ' +
                'class="size-6">' +
                '<path stroke-linecap="round" ' +
                'stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />' +
                '</svg>',
            navTitles: {
                days: 'MMMM yyyy',
            },

            onSelect({date, formattedDate, datepicker}) { return true; },
            onChangeViewDate({month, year, decade}) { return true; },
            onChangeView(view) { return true; },
            onRenderCell({date, cellType, datepicker}) { return true; },
            onShow(isAnimationComplete) { return true; },
            onHide(isAnimationComplete) { return true; },
            onClickDayName({dayIndex, datepicker}) { return true; },
        };

        for (let option in options) {
            // noinspection JSUnfilteredForInLoop
            this.options[option] = options[option]
        }
    }

    widget() {
        const airDatePicker = new AirDatepicker(this.selector, this.options);
    }
}

console.debug('air-calendar-module.js loaded');
