import React from 'react'
import './listblog.css'

function listblog(props) {
    return (
        <div className="card">
            <div className="card-body">
                <h5 className="card-title">{props.subject}</h5>
                <p className="card-text">{props.description}</p>
            </div>
        </div>
    )

}

export default listblog;