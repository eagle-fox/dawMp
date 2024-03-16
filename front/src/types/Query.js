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
      this.client.defaults.headers.common['Authorization'] =
          `Basic ${auth.encoded}`
    } else if (auth instanceof BearerToken) {
      this.client.defaults.headers.common['Authorization'] =
          `Bearer ${auth.token}`
    }
    return this
  }

    async login() {
        return await this.client
            .post(this.url + 'users/login', { responseType: 'json' })
            .then((response) => response.data)
            .catch((error) => {
                console.error('Error fetching users:', error.message)
                console.error('Error details:', error.response.data)
                throw error
            })
    }

  /**
   * @returns {Array[User]} - The list of users.
   */
  async getUsers() {
    const data = await this.client
        .get(this.url + 'users', { responseType: 'json' })
        .then((response) => response.data.users)
        .catch((error) => {
          console.error('Error fetching users:', error.message)
          console.error('Error details:', error.response.data)
          throw error
        })

    // convert the axios response to an normal array
    for (const i in data) {
      const myUser = new User()
      myUser.withObject(data[i])
      myUser.build()
      this.users.push(myUser)
    }

    return this.users
  }

  /**
   * @param {number} id - The ID of the user to get.
   * @returns {Promise<User>}
   */
  async getUser(id) {
    const data = await this.client
        .get(this.url + 'users/' + id, { responseType: 'json' })
        .then((response) => response.data)
        .catch((error) => {
          console.error('Error fetching user:', error.message)
          console.error('Error details:', error.response.data)
          throw error
        })

    const myUser = new User()
    myUser.withObject(data)
    myUser.build()

    return myUser
  }

  /**
   * @param {User} user - The user to create.
   * @returns {Promise<void>}
   */
  async postUser(user) {
    await this.client
        .post(this.url + 'users', user, { responseType: 'json' })
        .then((response) => response.data)
        .catch((error) => {
          console.error('Error posting user:', error.message)
          console.error('Error details:', error.response.data)
          throw error
        })
  }

  /**
   * @param {User} user - El ID del usuario a actualizar
   * @returns {Promise<void>}
   */
  async putUser(user) {
    await this.client
        .put(this.url + 'users/', user.id, { responseType: 'json' })
        .then((response) => response.data)
        .catch((error) => {
          console.error('Error putting user:', error.message)
          console.error('Error details:', error.response.data)
          throw error
        })
  }

  /**
   * @param {User} user - El ID a actualizar
   */
  async patchUser(user) {
    await this.client
        .patch(this.url + 'users/' + user.id, { responseType: 'json' })
        .then((response) => response.data)
        .catch((error) => {
          console.error('Error patching user:', error.message)
          console.error('Error details:', error.response.data)
          throw error
        })
  }

  /**
   * @param {number} id - El ID del usuario a eliminar
   * @throws {Error} - Si hay un error al eliminar el usuario
   */
  async deleteUser(id) {
    console.log('Deleting user with id:', id)
    try {
      const response = await this.client.delete(this.url + 'users/' + id, {
        responseType: 'json',
      })
      console.log('Delete response:', response)
      return response.data
    } catch (error) {
      console.error('Error deleting user:', error.message)
      console.error('Error details:', error.response.data)
      throw error
    }
  }

  /**
   * @method getIotDevices - Fetch all IoT devices
   * @returns {Promise<Array>} - The list of IoT devices
   */
  async getIotDevices() {
    let response = null
    try {
      response = await this.client.get(this.url + 'iotDevices', {
        responseType: 'json',
      })
    } catch (error) {
      console.error('Error fetching IoT devices:', error.message)
      console.error('Error details:', error.response.data)
      throw error
    }
    return response.data
  }

  /**
   * @method getIotDevice - Fetch a specific IoT device by its ID
   * @param {number} id - The ID of the IoT device
   * @returns {Promise<Object>} - The IoT device data
   */
  async getIotDevice(id) {
    const data = await this.client
        .get(this.url + 'iotDevices/' + id, { responseType: 'json' })
        .then((response) => response.data)
        .catch((error) => {
          throw error
        })
    return data
  }

  /**
   * @method postIotDevice - Create a new IoT device
   * @param {Object} iotDevice - The IoT device data
   */
  async postIotDevice(iotDevice) {
    try {
        const response = await this.client.post(this.url + 'iotDevices', iotDevice, { responseType: 'json' });
        return response.data; 
    } catch (error) {
        throw error;
    }
}

  /**
   * @method putIotDevice - Update an existing IoT device
   * @param {Object} iotDevice - The IoT device data
   */
  async putIotDevice(iotDevice) {
    await this.client
        .put(this.url + 'iotDevices/' + iotDevice.id, iotDevice, {
          responseType: 'json',
        })
        .catch((error) => {
          throw error
        })
  }

  /**
   * @method patchIotDevice - Partially update an existing IoT device
   * @param {Object} iotDevice - The IoT device data
   */
  async patchIotDevice(iotDevice) {
    await this.client
        .patch(this.url + 'iotDevices/' + iotDevice.id, iotDevice, {
          responseType: 'json',
        })
        .catch((error) => {
          throw error
        })
  }

  /**
   * @method deleteIotDevice - Delete an existing IoT device
   * @param {number} id - The ID of the IoT device
   */
  async deleteIotDevice(id) {
    await this.client
        .delete(this.url + 'iotDevices/' + id, { responseType: 'json' })
        .catch((error) => {
          throw error
        })
  }

  /**
   * @method getIotDevicesBySelf - Fetch all IoT devices associated with the authenticated user
   * @returns {Promise<Array>} - The list of IoT devices
   */
  async getIotDevicesBySelf() {
    let response = null
    try {
      response = await this.client.get(this.url + 'iotDevices/ByMyself', {
        responseType: 'json',
      })
    } catch (error) {
      console.error('Error fetching IoT devices:', error.message)
      console.error('Error details:', error.response.data)
      throw error
    }
    return response.data
  }
}

export default Query