import apiElement from './apiElement';

export default function apiEquals(api1, api2) {
    return apiElement(api1) === apiElement(api2);
}