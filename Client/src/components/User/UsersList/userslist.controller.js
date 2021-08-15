import React, { useEffect, useState } from "react";
import axios from "axios";


function UsersList() {

    const [userListState, setuserListState] = useState([])

    useEffect(() => {
        // fetchUsersNoComment();
        return () => {
            setuserListState([]);
        };
    }, [])

    useEffect(() => {
        console.log(userListState)
    }, [userListState])

    const fetchUsersNoComment = () => {
        axios.get('')
            .then((response) => {
                //API call
                const data = response.data
                console.log('blog data', data)
                if (data['Is Success'] === 0) {
                    alert(data['Message'])
                } else {
                    setuserListState(response.data['userlist'])
                }
            }).catch(error => {
                console.log('error', error)
            })
    }

    return (
        <div className="list-blog-bg">
            <div className="scrollit">
                <table className="table table-striped">
                    <tbody>
                        <h3>UsersList Who Never Posted a Comment</h3>
                        {/* {
                            userListState && userListState.map((user, index) => {
                                return (
                                    <tr key={index}>
                                        <td>
                                            <div className="card-header">
                                                {user}
                                            </div>
                                        </td>
                                    </tr>
                                )
                            })
                        } */}
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

export default UsersList;