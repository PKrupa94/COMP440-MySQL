import React, { useState, useEffect } from 'react'

const AuthContext = React.createContext({
    isLoggedIn: false,
    onLogout: () => { },
    onLogin: () => { }
})

export const AuthContextProvider = (props) => {

    const [isLoggedIn, setLoggedIn] = useState(false)

    useEffect(() => {
        const storedUserLoginInfo = localStorage.getItem('isUserLogin')
        if (storedUserLoginInfo === '1') {
            setLoggedIn(true)
        }
    }, [])

    const logInHandler = () => {
        localStorage.setItem('isUserLogin', '1')
        setLoggedIn(true)
    }

    const logoutHandler = () => {
        sessionStorage.removeItem('isUserLogin')
        setLoggedIn(false)
    }

    return <AuthContext.Provider value={{
        isLoggedIn: isLoggedIn,
        onLogin: logInHandler,
        onLogout: logoutHandler
    }}>
        {props.children}
    </AuthContext.Provider>
}

export default AuthContext;
