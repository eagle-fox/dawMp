class userSession {
    static #instance

    constructor(name, email, role, token) {
        // Verificamos si ya hay una instancia, si no existe la crea.
        if (userSession.#instance) {
            return userSession.#instance
        }

        // userSession variables
        this.name = name
        this.email = email
        this.role = role

        if (!this.#checkTokenFormat()) {
            token = null
        }
        this.token = token

        userSession.#instance = this
    }

    static getInstance() {
        if (!userSession.#instance) {
            userSession.#instance = new userSession()
        }
        return userSession.#instance
    }

    getData() {
        return this.data
    }

    #checkTokenFormat(token) {
        return /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.test(
            token,
        )
    }

    setData(name, email, role, token) {
        this.name = name
        this.email = email
        this.role = role

        if (!this.#checkTokenFormat()) {
            token = null
        }
        this.token = token
    }
}

export default userSession