import React from 'react'
import { Redirect } from 'react-router-dom';


function dashboard(props) {

    if (sessionStorage.getItem('isUserLogin') === null) {
        return <Redirect to="/sign-in" />;
    }

    return (
        <div>
            <h3>Welcome {props.location.state.username}!!</h3>
        </div>
    )
}

export default dashboard;