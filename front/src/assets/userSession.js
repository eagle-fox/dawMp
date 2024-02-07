class userSession {
    static #instance;

    constructor() {
        
        // Verificamos si ya hay una instancia, si no existe la crea.
        
        if (userSession.#instance) {
            return userSession.#instance;
        }

        // userSession variables

        this.data = "Example";
        userSession.#instance = this;
    }

    getData() {
        return this.data;
    }

    setData(newData) {
        this.data = newData;
    }

    static getInstance() {
        if (!userSession.#instance) {
            userSession.#instance = new userSession();
        }

        return userSession.#instance;
    }
}


export default userSession;