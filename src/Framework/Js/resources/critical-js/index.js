import addClass from './addClass';
import callOncePerAnimationFrame from './callOncePerAnimationFrame';
import debounceForAnimationFrame from './debounceForAnimationFrame';
import debounce from './debounce';
import Object_ from './Object_';
import hasClass from './hasClass';
import merge from './merge';
import removeClass from './removeClass';
import mix from './mix';
import find from './find';
import Config from './Config';
import isString from "./isString";
import ViewModel from "./ViewModel";
import getClassSuffix from './getClassSuffix';
import forEachParentElement from './forEachParentElement';
import firstParentElement from './firstParentElement';

import config from './vars/config';
import view_models from "./vars/view_models";

import "@babel/polyfill";

merge(window, {
    Manadev_Framework_Js: {
        addClass, callOncePerAnimationFrame, Object_, hasClass, merge, removeClass, mix, find,
        Config, isString, ViewModel, debounceForAnimationFrame, debounce, getClassSuffix,
        forEachParentElement, firstParentElement,
        vars: {config, view_models}
    }
});
