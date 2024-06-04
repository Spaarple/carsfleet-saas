import './styles/app.css';
import './collapseTool';
import './modal';

import {Calendar} from './calendar';

let calendar = document.querySelector('.airdpcalendar');
if (calendar !== null) {
    new Calendar('#borrow_form_startDate').widget();

}

console.log('This log comes from assets/app.js  🎉');
