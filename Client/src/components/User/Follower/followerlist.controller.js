import React, { useState } from 'react'
import axios from 'axios'
import './followerlist.css'

function Followers() {

    const [usernameState, setUsernameState] = useState({
        username1: '',
        username2: ''
    })

    const [arrUserState, setArrUserState] = useState([])

    const getFollowerHandler = (event) => {
        event.preventDefault()
        axios.post('http://localhost/COMP440/Server/api/GetFollowers.php', {
            username1: usernameState.username1,
            username2: usernameState.username2
        }).then((response) => {
            const data = response.data
            console.log('userlist', data['userlist'])
            if (data['Is Success'] === 0) {
                alert(data['Message'])
            } else {
                setArrUserState(data['userlist'])
            }
        }).catch(error => {
            console.log('error', error)
        })
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
                    onClick={getFollowerHandler}>Get Followers</button>
            </form>
            <div className="scrollit comment-list">
                <h3>Followers</h3>
                <table className="table table-striped">
                    <tbody>
                        {
                            arrUserState && arrUserState.map((user, index) => {
                                return (
                                    <tr key={index}>
                                        <td>
                                            <div className="card-header">
                                                {user.username}
                                            </div>
                                        </td>
                                    </tr>
                                )
                            })
                        }
                    </tbody>
                </table>
            </div>
        </div>
    )

}

export default Followers;