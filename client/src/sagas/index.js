import { all, put, call, takeEvery } from "redux-saga/effects"

import {
    GET_PLAYERS_STATS,
    LOGIN_USER,
    REGISTER_USER,
    setCurrentUser, setPlayersStats
} from "../app/actions";

import {
    fetchPlayersStatsService,
    loginUserService,
    registerUserService
} from "../services";


function* loginUser(action) {
    try {
        let response = yield call(
            loginUserService,
            action.payload.nickname,
            action.payload.password
        );

        yield put(setCurrentUser(response.data.token))
    } catch (e) {

    }
}

function* registerUser(action) {
    try {
        let response = yield call(
            registerUserService,
            action.payload.nickname,
            action.payload.password
        );

        yield put(setCurrentUser(response.data.token))
    } catch (e)  {

    }
}

function* fetchPlayersStats(action)
{
    try {
        const {data} = yield call(
            fetchPlayersStatsService()
        )

        yield put(setPlayersStats(data))

    } catch (e) {}
}

function* watchFetchAuthenticate() {
    yield takeEvery(LOGIN_USER, loginUser)
}

function* watchFetchRegister() {
    yield takeEvery(REGISTER_USER, registerUser)
}

function* watchFetchPlayersStats()
{
    yield takeEvery(GET_PLAYERS_STATS, fetchPlayersStats);
}

export default function* rootSaga() {
    yield all([
        watchFetchAuthenticate(),
        watchFetchRegister(),
        watchFetchPlayersStats()
    ])
}