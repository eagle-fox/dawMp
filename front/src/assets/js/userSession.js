import URL from '@/types/URL.js'
import Query from '@/types/Query.js'
import BearerToken from '@/types/BearerToken.js'

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

    setIotDevicesData(data) {
        this.iotDevices = data;
    }

    getIotDevices() {
        return this.iotDevices;
    } 

    parseUrl(url) {
        const urlObj = new URL(url);
        const protocol = urlObj.protocol.replace(':', '');
        const hostname = urlObj.hostname;
        const port = urlObj.port || (protocol === 'https' ? '443' : '80');
  
        return [protocol, hostname, port];
    }

    #checkTokenFormat(token) {
        return /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.test(
            token,
        )
    }

    async #getIotDevicesData(token){
        let connectData = this.parseUrl(this.$config.devConfig.apiServer);
        let myUrl = new URL(connectData[0], connectData[1], connectData[2])
        let query = new Query(myUrl).withAuth(new BearerToken(this.token))
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