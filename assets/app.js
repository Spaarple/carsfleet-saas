import './styles/app.css';
import './collapseTool';
import './modal';

import {Calendar} from './calendar';

let calendar = document.querySelector('.airdpcalendar');
if (calendar !== null) {
    new Calendar('#borrow_form_startDate', {
        onRenderCell({date, cellType, datepicker}) {
            let dateString = date.toISOString().split('T')[0];

            // Vérifiez si la date est dans les dates d'emprunt
            let isBorrowDate = borrowDates.some(borrowDate => {
                let startDate = new Date(borrowDate.start + 'T00:00:00');
                let endDate = new Date(borrowDate.end + 'T23:59:59');

                return dateString >= startDate.toISOString().split('T')[0] &&
                    dateString < endDate.toISOString().split('T')[0];
            });

            if (isBorrowDate) {
                return {classes: 'already-borrowed', disabled: true}
            }
        },
    }).widget();
}

console.log('This log comes from assets/app.js  🎉');
