import axios from "axios";


// Import configuration

import config from "../config.json";
let devConfig = config.devConfig;

class ConnectionApi {
  constructor() {
    if (!ConnectionApi.instance) {
      this.axiosInstance = axios.create();
      ConnectionApi.instance = this;
    }

    return ConnectionApi.instance;
  }

  async testAxios() {
    try {
      // Test Axios connection  

      const response = await this.axiosInstance.get('https://jsonplaceholder.typicode.com/todos/1');
      console.log('Axios works! Response:', response.data);
      console.log(devConfig.apiServer);
    } catch (error) {
      console.error('Axios error:', error.message);
    }
  }

  async makeUser() {
    try {

      const formData = new FormData();
      formData.append('nombre', 'Yeison');
      formData.append('nombre_segundo', 'Rascado');
      formData.append('apellido_primero', 'González');
      formData.append('apellido_segundo', 'Rascado');
      formData.append('email', 'perico@yeison.com');
      formData.append('password', 'yeison');
      formData.append('rol', 'ADMIN');

      const headers = {
        'Authorization': `Basic ${devConfig.authCode}`,
        'Content-Type': 'multipart/form-data',
      };

      const response = await axios.post(`${devConfig.apiServer}/users`, formData, { headers });

      console.log('Axios works! Response:', response.data);
    } catch (error) {
      console.error('Axios error:', error.message);
    }
  }
}

export default ConnectionApi;
