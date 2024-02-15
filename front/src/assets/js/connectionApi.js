import axios from "axios";

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
      const response = await this.axiosInstance.get('https://jsonplaceholder.typicode.com/todos/1');
      console.log('Axios works! Response:', response.data);
    } catch (error) {
      console.error('Axios error:', error.message);
    }
  }
}

export default ConnectionApi;
