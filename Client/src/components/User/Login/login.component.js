import React, { useState, useContext } from 'react'
import axios from 'axios'
import AuthContext from '../../../AuthContext'

function Login(props) {

    const [state, setLoginState] = useState({
        userName: '',
        password: ''
    })
    const [validationError, setValidationError] = useState({
        isError: false,
        message: ''
    })

    const context = useContext(AuthContext)

    const signInHandler = (event) => {
        event.preventDefault();
        axios.post('http://localhost/COMP440/Server/api/Login.php', {
            username: state.userName,
            password: state.password
        }).then(response => {
            const data = response['data']
            if (data['Is Success'] === 0) {
                setValidationError({
                    isError: true,
                    message: data['Message']
                })
            } else {
                context.onLogin(data['userid'])
                props.history.push('/home', { username: state.userName })
            }
        }).catch(error => {
            console.log(error)
        })
    }

    return (
        <div className="auth-inner">
            <form onSubmit={signInHandler}>
                <h3>Sign In</h3>
                {validationError.isError ?
                    <div class="alert alert-danger" role="alert">
                        {validationError.message}
                    </div> : ''}
                <div className="form-group mb-3">
                    <label>Username</label>
                    <input type="text"
                        className="form-control"
                        required placeholder="Enter Usename"
                        onChange={e => setLoginState({ ...state, userName: e.target.value })}
                    />
                </div>
                <div className="form-group mb-3">
                    <label>Password</label>
                    <input type="password"
                        className="form-control"
                        required placeholder="Enter password"
                        onChange={e => setLoginState({ ...state, password: e.target.value })}
                    />
                </div>
                <button type="submit" className="btn btn-primary btn-block button_custom">Sign In</button>
                <p className="forgot-password text-right">
                    Create New Account <a href="/sign-up">sign up?</a>
                </p>
            </form>
        </div>
    )
}

export default Login;