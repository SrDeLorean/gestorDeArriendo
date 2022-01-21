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
                if (response.data.accessToken) {
                    localStorage.setItem('user', JSON.stringify(response.data));
                    localStorage.setItem('userData', JSON.stringify(response.data.userData));
                    localStorage.setItem('accessToken', JSON.stringify(response.data.accessToken));
                    localStorage.setItem('refreshToken', JSON.stringify(response.data.accessToken));
                }

                return response.data;
            }).catch((error) => {
                console.log(error)
            });
    }

    logout() {
        localStorage.removeItem('user');
    }

    register(user) {
        return axios.post(API_URL + 'register', {
            username: user.username,
            email: user.email,
            password: user.password
        });
    }
}

export default new AuthService();