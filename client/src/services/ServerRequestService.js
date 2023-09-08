import axios from "axios";
import {
    LANDS_BY_USER_URL,
    LOGIN_URL,
    REGISTER_URL,
    REPORTS_BY_UUID_URL, REPORTS_DEACTIVATE_URL,
    REPORTS_POST_MESSAGE_URL,
    REPORTS_URL
} from "../utils/ServerRequests";

export function loginRequest(request)
{
    return axios.post(LOGIN_URL, request);
}

export function registerRequest(request)
{
    return axios.post(REGISTER_URL, request);
}

export function getReportsRequest(token)
{
    const configHeaders = {
        headers: {
            'Authorization': `Bearer ` + token
        }
    }

    return axios.get(REPORTS_URL, configHeaders);
}

export function getReportByUUIDRequest(token, uuid)
{
    const configHeaders = {
        headers: {
            'Authorization': `Bearer ` + token
        }
    }

    return axios.get(REPORTS_BY_UUID_URL + uuid, configHeaders);
}

export function postReportMessageRequest(token, uuid, message)
{
    const configHeaders = {
        headers: {
            'Authorization': `Bearer ` + token
        }
    }

    return axios.post(REPORTS_POST_MESSAGE_URL + uuid, message, configHeaders);
}

export function deactivateReportRequest(token, uuid)
{
    const configHeaders = {
        headers: {
            'Authorization': `Bearer ` + token
        }
    }

    return axios.get(REPORTS_DEACTIVATE_URL + uuid, configHeaders);
}

export function getLandsByUser(token)
{
    const configHeaders = {
        headers: {
            'Authorization': `Bearer ` + token
        }
    }

    return axios.get(LANDS_BY_USER_URL, configHeaders);
}