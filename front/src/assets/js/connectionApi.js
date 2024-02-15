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
      // Test Axios connection  

      const response = await this.axiosInstance.get('https://jsonplaceholder.typicode.com/todos/1');
      console.log('Axios works! Response:', response.data);
    } catch (error) {
      console.error('Axios error:', error.message);
    }
  }

  async makeUser() {
    try {
      // Datos para enviar en el cuerpo de la solicitud
      const formData = new FormData();
      formData.append('nombre', 'Yeison');
      formData.append('nombre_segundo', 'Rascado');
      formData.append('apellido_primero', 'González');
      formData.append('apellido_segundo', 'Rascado');
      formData.append('email', 'theyeison@yeison.com');
      formData.append('password', 'yeison');
      formData.append('rol', 'ADMIN');

      // Configuración de los encabezados
      const headers = {
        'Authorization': 'Basic YWRtaW5AYWRtaW4uY29tOmFkbWlu',
        'Content-Type': 'multipart/form-data',
      };

      const response = await axios.post('http://localhost:2003/users', formData, { headers });

      console.log('Axios works! Response:', response.data);
    } catch (error) {
      console.error('Axios error:', error.message);
    }
  }
}

export default ConnectionApi;
