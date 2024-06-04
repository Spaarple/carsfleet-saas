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
                prevHtml: '<i class="fa-solid fa-angle-left" aria-hidden="true"></i>',
                nextHtml: '<i class="fa-solid fa-angle-right" aria-hidden="true"></i>',
                navTitles: {
                    days: 'MMMM yyyy',
                }
            });
        });
    }
}

console.debug('air-calendar.js loaded');