class userSession {
    static #instance;
    constructor(name, email, token) {
        // Verificamos si ya hay una instancia, si no existe la crea.
        if (userSession.#instance) {
            return userSession.#instance;
        }

        // userSession variables
        this.name = name;
        this.email = email;
        this.token = token;

        userSession.#instance = this;
    }

    getData() {
        return this.data;
    }

    setData(name, email, token) {
        this.name = name;
        this.email = email;
        this.token = token;
    }

    parseTokenInfo(token) {
        parseInt(token);
    }

    static getInstance() {
        if (!userSession.#instance) {
            userSession.#instance = new userSession();
        }

        return userSession.#instance;
    }
}


export default userSession;