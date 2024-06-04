import AirDatepicker from 'air-datepicker';
import LocalFr from 'air-datepicker/locale/fr';

//import './styles/calendar.scss';

export class Calendar {

    static widget() {
        document.querySelectorAll('.airdpcalendar').forEach(item => {
            const viewCalendar = item.dataset.viewCalendar;
            const dateFormat = item.dataset.dateFormat;
            const viewInline = item.dataset.viewInline;
            const minDate = Date.parse(item.dataset.minDate);
            const maxDate = Date.parse(item.dataset.maxDate);
            const range = item.dataset.range;

            const airDatePicker = new AirDatepicker('#' + item.id, {
                range: range,
                multipleDatesSeparator: ' - ',
                timepicker: true,
                locale: LocalFr,
                view: viewCalendar,
                dateFormat: dateFormat,
                inline: viewInline,
                minDate: minDate,
                maxDate: maxDate,
                minView: 'months',
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
                }
            });
        });
    }
}

console.debug('air-calendar.js loaded');