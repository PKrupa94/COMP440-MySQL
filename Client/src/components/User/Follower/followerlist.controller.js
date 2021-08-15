import React, { useState } from 'react'
import axios from 'axios'
import './followerlist.css'

function Followers() {

    const [usernameState, setUsernameState] = useState({
        username1: '',
        username2: ''
    })

    const getFollowerHandler = () => {
        console.log('button click')
    }

    return (
        <div className="list-blog-bg">
            <form className="form-width">
                <div className="form-group article-padding-bottom">
                    <label>Enter Username1</label>
                    <input type="text"
                        className="form-control"
                        placeholder="Enter Username1" required
                        onChange={e => setUsernameState({ ...usernameState, username1: e.target.value })}
                    />
                </div>
                <div className="form-group">
                    <label>Enter Username2</label>
                    <input type="text"
                        className="form-control"
                        placeholder="Enter Username2" required
                        onChange={e => setUsernameState({ ...usernameState, username2: e.target.value })}
                    />
                </div>
                <button type="submit"
                    className="article-button"
                    onClick={() => getFollowerHandler}>Get Followers</button>
            </form>
            <div className="scrollit comment-list">
                <h3>Followers</h3>
                <table className="table table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <div className="card-header">
                                    Username
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div className="card-header">
                                    Username
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div className="card-header">
                                    Username
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    )

}

export default Followers;