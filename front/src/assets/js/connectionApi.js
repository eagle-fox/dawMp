import axios from 'axios'

// Import configuration
import config from '../config.json'

let devConfig = config.devConfig

class ConnectionApi {
  constructor() {
    if (!ConnectionApi.instance) {
      this.axiosInstance = axios.create()
      ConnectionApi.instance = this
    }

    return ConnectionApi.instance
  }

  async testAxios() {
    try {
      // Test Axios connection

      const response = await this.axiosInstance.get(
        'https://jsonplaceholder.typicode.com/todos/1',
      )
      console.log('Axios works! Response:', response.data)
      console.log(devConfig.apiServer)
    } catch (error) {
      console.error('Axios error:', error.message)
    }
  }

  async getUserData(userToken) {
    try {
    } catch (error) {
    }
  }

  async makeUser(userData) {
    try {
      const formData = new FormData()

      let userPropertiesRequired = [
        'nombre',
        'nombre_segundo',
        'apellido_primero',
        'apellido_segundo',
        'email',
        'password',
      ]

      userPropertiesRequired.forEach((key) => {
        if (userData.hasOwnProperty(key)) {
          formData.append(key, userData[key])
        } else {
          console.error(`Campo obligatorio '${key}' no proporcionado.`)
        }
      })

      formData.append('rol', 'ADMIN')

      const headers = {
        Authorization: `Basic ${devConfig.authCode}`,
        'Content-Type': 'multipart/form-data',
      }

      const response = await axios.post(
        `${devConfig.apiServer}/users`,
        formData,
        { headers },
      )

      // console.log('Axios works! Response:', response.data.message);
      return true
    } catch (error) {
      console.error(
        'Axios error. Response from server:',
        error.response.data.message,
      )

      return error.response.data.message
    }
  }
}

export default ConnectionApi