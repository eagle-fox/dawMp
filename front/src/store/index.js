import { createStore } from 'vuex';
import userSession from '../assets/userSession';

const store = createStore({
    mutations: {
        setUserSession(state, userSession) {
            state.userSession = userSession;
        },
    },
    actions: {
        createNewUserSession({ commit }, userData) {
            return new Promise((resolve, reject) => {
                try {
                    const newUserSession = new userSession(userData.firstName, userData.email, userData.token);
                    commit('setUserSession', newUserSession);
                    resolve();
                } catch (error) {
                    reject(error);
                }
            });
        },
    },
    getters: {
        getUserSession: state => state.userSession,
    },
});


export default store;