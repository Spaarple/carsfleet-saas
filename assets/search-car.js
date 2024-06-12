'use strict';

import {Calendar} from './calendar';

let searchBy = document.querySelectorAll('.airdpcalendar');

searchBy.forEach((element) => {
    new Calendar('#'+element.id).widget();
});