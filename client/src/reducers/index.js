import {combineReducers} from '@reduxjs/toolkit'
import {
    END_PAGE_LOADING,
    LOGOUT_USER,
    SET_CURRENT_USER,
    SET_PLAYERS_STATS, START_PAGE_LOADING
} from "../app/actions";


function userReducer(state=null, action)
{
    switch(action.type)
    {
        case SET_CURRENT_USER:
            return {...state, user: action.payload.user};

        case LOGOUT_USER:
            return {};

        default:
            return state;
    }
}

function playerStatsReducer(state=null, action)
{
    switch(action.type)
    {
        case SET_PLAYERS_STATS:
            return {...state, stats: action.payload.stats};

        default:
            return state;
    }
}


function loadingReducer(state={}, action)
{
    switch (action.type)
    {
        case START_PAGE_LOADING:
            return {...state, isLoading: true};

        case END_PAGE_LOADING:
            return {...state, isLoading: false};

        default:
            return state;
    }
}

const mainReducer = combineReducers({
        consumer: userReducer,
        playerStats: playerStatsReducer,
        loading: loadingReducer
    }
)


export default mainReducer;