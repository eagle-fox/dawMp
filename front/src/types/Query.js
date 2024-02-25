import BasicAuth from '@/types/BasicAuth.js'
import BearerToken from '@/types/BearerToken.js'
import axios from 'axios'
import User from '@/types/User.js'

class Query {
    /**
     * @property {Array[User]} users - The list of users.
     * @property {URL} url - The URL.
     */
    users = []
    url = null

    /**
     * @constructor
     * @param {URL} url - The URL.
     */
    constructor(url) {
        this.url = url
        this.client = axios.create()
    }

    /**
     * @param {BasicAuth|BearerToken} auth - The authentication to use for the request.
     */
    withAuth(auth) {
        if (auth instanceof BasicAuth) {
            this.client.defaults.headers.common['Authorization'] = `Basic ${auth.encoded}`
        } else if (auth instanceof BearerToken) {
            this.client.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`
        }
        return this
    }

    /**
     * @returns {Array[User]} - The list of users.
     */
    async getUsers() {
        const data = await this.client.get(this.url + 'users', { responseType: 'json' })
            .then(response => response.data.users)
            .catch(error => {
                console.error('Error fetching users:', error.message)
                console.error('Error details:', error.response.data)
                throw error
            })

        // convert the axios response to an normal array
        for (const i in data) {
            this.users.push(new User(data[i]))
        }

        return this.users
    }

}

export default Query