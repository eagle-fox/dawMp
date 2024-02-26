import { createStore } from 'vuex'
import userSession from '../assets/js/userSession'

const store = createStore({
    mutations: {
        setUserSession(state, userSession) {
            state.userSession = userSession
        },
        clearSession(state, clearData) {
            state.userSession.setData(
                clearData.name,
                clearData.email,
                clearData.role,
                clearData.token,
            )
        },
        makeVisitorSession(state, clearData) {
            state.userSession = new userSession(
                clearData.name,
                clearData.email,
                clearData.role,
            )
        },
    },
    actions: {
        createNewUserSession({ commit }, userData) {
            return new Promise((resolve, reject) => {
                try {
                    const newUserSession = new userSession(
                        userData.name,
                        userData.email,
                        userData.role,
                        userData.token,
                    )
                    commit('setUserSession', newUserSession)
                    resolve()
                } catch (error) {
                    reject(error)
                }
            })
        },
        clearUserSession({ commit }) {
            return new Promise((resolve, reject) => {
                try {
                    let clearData = {
                        name: 'unknow',
                        email: 'unknow@gmail.com',
                        role: 'unknow',
                        token: '',
                    }
                    commit('clearSession', clearData)
                    resolve()
                } catch (error) {
                    reject(error)
                }
            })
        },
        makeVisitorSession({ commit }) {
            return new Promise((resolve, reject) => {
                try {
                    let clearData = {
                        name: 'visitor',
                        email: 'visitor@gmail.com',
                        role: 'visitor',
                        token: null,
                    }
                    commit('makeVisitorSession', clearData)
                    resolve()
                } catch (error) {
                    reject(error)
                }
            })
        },
    },
    getters: {
        getUserSession: (state) => state.userSession,
    },
})

export default store