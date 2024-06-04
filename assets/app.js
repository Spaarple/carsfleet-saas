import './styles/app.css';
import './collapseTool';
import './modal';

import {Calendar} from './calendar';

let calendar = document.querySelector('.airdpcalendar');
if (calendar !== null) {
    new Calendar.widget();
}

console.log('This log comes from assets/app.js  🎉');
