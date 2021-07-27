import React, { useState, useContext } from 'react'
import axios from 'axios'
import AuthContext from '../../../AuthContext'

function Login(props) {

    const [state, setLoginState] = useState({
        userName: '',
        password: ''
    })

    const context = useContext(AuthContext)

    const signInHandler = (event) => {
        event.preventDefault();
        console.log('sign in click')

        //Need to change url
        axios.post('http://localhost/COMP440/Server/api/Login.php', {
            username: state.userName,
            password: state.password
        }).then(response => {
            console.log('response', response)
            context.onLogin()
            props.history.push('/')
        }).catch(error => {
            console.log(error)
        })
    }

    return (
        <div>
            <form onSubmit={signInHandler}>
                <h3>Sign In</h3>
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
                {/* <div className="form-group mb-3">
                    <div className="custom-control custom-checkbox">
                        <input type="checkbox" className="custom-control-input" id="customCheck1" />
                        <label className="custom-control-label" htmlFor="customCheck1">Remember me</label>
                    </div>
                </div> */}
                <button type="submit" className="btn btn-primary btn-block button_custom">Sign In</button>
                {/* <p className="forgot-password text-right">
                    Forgot <a href="#">password?</a>
                </p> */}
                <p className="forgot-password text-right">
                    Create New Account <a href="/sign-up">sign up?</a>
                </p>
            </form>
        </div>
    )
}

export default Login;