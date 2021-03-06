import { AUTH_LOGIN, PREF_THEME, PREF_LANG, IS_LOADED } from "./types";

export const login = (data => {
    return {
        type: AUTH_LOGIN,
        data
    }
})

export const theme = (data => {
    return {
        type: PREF_THEME,
        data
    }
})

export const lang = (data => {
    return {
        type: PREF_LANG,
        data
    }
})

export const loaded = (data => {
    return {
        type: IS_LOADED,
        data
    }
})