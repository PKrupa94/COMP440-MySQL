import React, { useState, useEffect } from 'react'


const AuthContext = React.createContext({
    isLoggedIn: false,
    onLogout: () => { },
    onLogin: (userId) => { },

})

export const AuthContextProvider = (props) => {

    const [isLoggedIn, setLoggedIn] = useState(false)

    useEffect(() => {
        const storedUserLoginInfo = sessionStorage.getItem('isUserLogin')
        if (storedUserLoginInfo === '1') {
            setLoggedIn(true)
        }
    }, [])

    const logInHandler = (userId) => {
        sessionStorage.setItem('isUserLogin', '1')
        sessionStorage.setItem('userId', userId)
        setLoggedIn(true)
    }

    const logoutHandler = () => {
        sessionStorage.removeItem('isUserLogin')
        sessionStorage.removeItem('userId')
        setLoggedIn(false)
        window.location.reload()
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
