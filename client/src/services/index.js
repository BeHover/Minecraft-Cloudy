import axios from "axios";

const BASE_URL = "https://127.0.0.1:8000";
const LOGIN_USER = BASE_URL.concat('/login');
const REGISTER_USER = BASE_URL.concat('/register');
const GET_PLAYERS_STATS = BASE_URL.concat('/stats');


export function loginUserService(nickname, password)
{
    return axios.post(LOGIN_USER, {nickname, password});
}

export function registerUserService(nickname, password)
{
    return axios.post(REGISTER_USER, {nickname, password});
}

export function fetchPlayersStatsService()
{
    return axios.get(GET_PLAYERS_STATS);
}