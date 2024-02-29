import URL from '@/types/URL.js'
import Query from '@/types/Query.js'
import BearerToken from '@/types/BearerToken.js'

class userSession {
    static #instance
    constructor(name, email, role, token, iotDevices) {
        // Verificamos si ya hay una instancia, si no existe la crea.
        if (userSession.#instance) {
            return userSession.#instance
        }

        // userSession variables
        this.name = name
        this.email = email
        this.role = role
        this.iotDevices = null;

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

    setIotDevicesData(data) {
        this.iotDevices = data;
    }

    getIotDevices() {
        return this.iotDevices;
    } 

    #checkTokenFormat(token) {
        return /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.test(
            token,
        )
    }

    async #getIotDevicesData(token){
        let myUrl = new URL('http', 'localhost', 2003)
        let query = new Query(myUrl).withAuth(new BearerToken('9c4ca426-54e4-4326-a882-fc2f71d5f1cd'))
        let response = await query.getIotDevicesBySelf()
        response = response.data;

        console.log(response);
    }

    setData(name, email, role, token) {
        this.name = name
        this.email = email
        this.role = role
        this.token = token
    }


}

export default userSession