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
        updateUserSession(state, userData){
            state.userSession.setData(
                userData.name,
                userData.email,
                userData.role,
                userData.token
            )
        },
        makeVisitorSession(state, clearData) {
            state.userSession = new userSession(
                clearData.name,
                clearData.email,
                clearData.role,
            )
        },setIotDevices(state,data){
            state.userSession.setIotDevicesData(data);
        }
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
        updateUserSession({ commit }, userData) {
            return new Promise((resolve, reject) => {
                try {
                    commit('updateUserSession', userData)
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
                        iotDevices: []
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
        },setIotDevices({ commit }, iotData) {
            return new Promise((resolve, reject) => {
                try {
                    commit('setIotDevices', iotData)
                    resolve()
                } catch (error) {
                    reject(error)
                }
            })
        }

    },
    getters: {
        getUserSession: (state) => state.userSession,
        getIotDevices: (state) => state.userSession.getIotDevices()
    },
})

export default store