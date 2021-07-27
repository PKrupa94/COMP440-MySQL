import React, { useState, useContext } from 'react'
import axios from 'axios'
import AuthContext from '../../../AuthContext'


function Signup(props) {

    const initialState = {
        firstName: '',
        lastName: '',
        userName: '',
        email: '',
        password: '',
        confirmPassword: ''
    }

    const context = useContext(AuthContext)

    //handle usedata
    const [state, setNewState] = useState({ initialState })
    const [error, setError] = useState('')
    const [validationError, setValidationError] = useState({
        isError: false,
        message: ''
    })

    //signup click
    const signUpHandler = (event) => {
        event.preventDefault();
        console.log('btn click')
        if (state.password !== state.confirmPassword) {
            setError('Password does not match')
        } else {
            //API call

            //Need to change url
            axios.post('http://localhost/COMP440/Server/api/Register.php', {
                firstname: state.firstName,
                lastname: state.lastName,
                username: state.userName,
                email: state.email,
                password: state.password
            }).then(response => {
                console.log('response', response)
                const data = response['data']
                if (data['Is Success'] == 0) {
                    setValidationError({
                        isError: true,
                        message: data['Message']
                    })
                } else {
                    context.onLogin()
                    props.history.push('/', { username: state.userName })
                }
            }).catch(error => {
                console.log(error)
            })
        }
    }

    return (
        <div>
            <form onSubmit={signUpHandler}>
                <h3>Sign Up</h3>
                {validationError.isError ?
                    <div class="alert alert-danger" role="alert">
                        {validationError.message}
                    </div> : ''}
                <div className="form-group mb-3">
                    <label>First name</label>
                    <input type="text"
                        className="form-control"
                        required placeholder="First name"
                        onChange={e => setNewState({ ...state, firstName: e.target.value })}
                    />
                </div>
                <div className="form-group mb-3">
                    <label>Last name</label>
                    <input type="text"
                        className="form-control"
                        required placeholder="Last name"
                        onChange={e => setNewState({ ...state, lastName: e.target.value })}
                    />
                </div>
                <div className="form-group mb-3">
                    <label>UserName</label>
                    <input type="text"
                        className="form-control"
                        required placeholder="User Name"
                        onChange={e => setNewState({ ...state, userName: e.target.value })}
                    />
                </div>
                <div className="form-group mb-3">
                    <label>Email address</label>
                    <input type="email"
                        className="form-control"
                        required placeholder="Enter email"
                        onChange={e => setNewState({ ...state, email: e.target.value })}
                    />
                </div>
                <div className="form-group mb-3">
                    <label>Password</label>
                    <input type="password"
                        className="form-control"
                        required placeholder="Enter password"
                        onChange={e => setNewState({ ...state, password: e.target.value })}
                    />
                </div>
                <div className="form-group mb-3">
                    <label>Confirm Password</label>
                    <input type="password"
                        className="form-control"
                        required placeholder="Enter Confirm password"
                        onChange={e => setNewState({ ...state, confirmPassword: e.target.value })}
                    />
                    <div className="text-danger">{error}</div>
                </div>
                <button type="submit" className="btn btn-primary btn-block button_custom">Sign Up</button>
                <p className="forgot-password text-right">
                    Already registered <a href="/sign-in">sign in?</a>
                </p>
            </form>
        </div>
    )

}

export default Signup;