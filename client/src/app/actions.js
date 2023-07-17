export const LOGIN_USER = "LOGIN_USER";
export const REGISTER_USER = "REGISTER_USER";
export const SET_CURRENT_USER = "SET_CURRENT_USER";
export const LOGOUT_USER = "LOGOUT_USER";
export const GET_PLAYERS_STATS = "GET_PLAYERS_STATS";
export const SET_PLAYERS_STATS = "GET_PLAYERS_STATS";
export const START_PAGE_LOADING = "START_PAGE_LOADING";
export const END_PAGE_LOADING = "END_PAGE_LOADING";


export function loginUser(nickname, password)
{
    return {
        type: LOGIN_USER,
        payload: {nickname, password}
    }
}

export function registerUser(
    nickname,
    password
)
{
    return {
        type: REGISTER_USER,
        payload: {
            nickname,
            password
        }
    }
}

export function setCurrentUser(user)
{
    return {
        type: SET_CURRENT_USER,
        payload: {user}
    }
}

export function logoutUser()
{
    return {
        type: LOGOUT_USER,
        payload: {}
    }
}

export function getPlayersStats()
{
    return {
        type: GET_PLAYERS_STATS,
        payload: {}
    }
}

export function setPlayersStats(data)
{
    return {
        type: SET_PLAYERS_STATS,
        payload: {data}
    }
}

export function startPizzaLoading()
{
    return {
        type: START_PAGE_LOADING,
        payload: {}
    }
}

export function endPizzaLoading()
{
    return {
        type: END_PAGE_LOADING,
        payload: {}
    }
}