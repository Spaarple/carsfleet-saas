'use strict';

// set the target element that will be collapsed or expanded (eg. navbar menu)
const $targetEl = document.getElementById('targetEl');

const instanceOptions = {
    id: 'targetEl',
    override: true
};

import { Collapse } from 'flowbite';

/*
 * $targetEl: required
 * $triggerEl: optional
 * options: optional
 */
const collapse = new Collapse($targetEl, null, null, instanceOptions);

