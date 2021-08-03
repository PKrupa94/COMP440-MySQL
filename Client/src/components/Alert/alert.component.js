import React from 'react'
import './alert.css'

function Alert(props) {

    const successBtnHandler = () => {
        console.log('success btn click')
        // props.history.push('/')
    }

    return (
        <div className="container">
            <div className="col-md-12">
                <div id="errorsViewAndAck">
                    <div className="alert alert-success">
                        <div>
                            <span><strong>{props.message}</strong></span>
                            <p className="btn btn-success" onClick={successBtnHandler}>OK</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    )
}

export default Alert;
