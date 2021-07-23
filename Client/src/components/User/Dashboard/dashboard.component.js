import React from 'react'
import { Redirect } from 'react-router-dom';


function dashboard() {

    if (sessionStorage.getItem('isUserLogin') === null) {
        return <Redirect to="/sign-in" />;
    }

    return (
        <div>
            <h3>Welcome to home page</h3>
        </div>
    )
}

export default dashboard;