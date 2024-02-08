import { createStore } from 'vuex';
import userSession from '../assets/userSession';

const store = createStore({
    mutations: {
        setUserSession(state, userSession) {
            state.userSession = userSession;
        },
        clearSession(state, clearData){
            state.userSession.setData(clearData.name, clearData.email, clearData.role, clearData.token)
        }
    },
    actions: {
        createNewUserSession({ commit }, userData) {
            return new Promise((resolve, reject) => {
                try {
                    const newUserSession = new userSession(userData.name, userData.email, userData.role ,userData.token);
                    commit('setUserSession', newUserSession);
                    resolve();
                } catch (error) {
                    reject(error);
                }
            });
        },
        clearUserSession({ commit }, newToken){
            return new Promise((resolve, reject) => {
                try {

                    let clearData = {
                        name: 'visitor',
                        email: 'visitor@gmail.com',
                        role: 'visitor',
                        token: newToken
                    }
                    commit('clearSession', clearData);
                    resolve();
                } catch (error) {
                    reject(error);
                }
            });
        }
    },
    getters: {
        getUserSession: state => state.userSession,
    },
});


export default store;