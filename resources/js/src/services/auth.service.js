import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api/';

class AuthService {
    login(user) {
        return axios
            .post(API_URL + 'login', {
                email: user.email,
                password: user.password
            })
            .then(response => {
                localStorage.setItem('userData', JSON.stringify(response.data.userData));
                localStorage.setItem('accessToken', JSON.stringify(response.data.accessToken));
                let mensaje = {
                    title: `Bienvenido ${response.data.userData.fullName || response.data.userData.username}`,
                    text: `Ha iniciado sesión correctamente como ${response.data.userData.role}. ¡Ya puedes empezar a explorar!`,
                }
                response.data.mensaje = mensaje
                return response.data;
            }).catch((error) => {
                let mensaje = {
                    title: "",
                    text: ""
                }
                console.log(error.message)
                if (error.message == 'Request failed with status code 500') {
                    mensaje.title = 'Problemas de conexion'
                    mensaje.text = 'Error al comunicarse con el servidor, intente más tarde';
                } else {
                    if (error.response.data.success == false) {
                        mensaje.title = error.response.data.message
                        mensaje.text = error.response.data.data.error
                    }
                }
                return mensaje;
            })

    }

    logout() {
        localStorage.removeItem('user');
    }

    register(user) {
        return axios
            .post(API_URL + 'register', {
                fullName: user.fullName,
                email: user.email,
                password: user.password
            })
            .then(response => {
                localStorage.setItem('userData', JSON.stringify(response.data.userData));
                localStorage.setItem('accessToken', JSON.stringify(response.data.accessToken));
                return response
            }).catch((error) => {
                console.log(error.message)
                return error;
            })
    }
}

export default new AuthService();