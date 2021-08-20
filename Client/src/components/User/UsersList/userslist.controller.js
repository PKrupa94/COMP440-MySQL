import React, { useEffect, useState, useRef } from "react";
import axios from "axios";
import { BiSearch } from 'react-icons/bi'


function UsersList() {
    const dateRef = useRef();
    const [userListState, setuserListState] = useState([{}])
    const [isUserFromSearch, setUserFromSearch] = useState(false)

    useEffect(() => {
        // fetchUsersNoComment();
        return () => {
            // setuserListState([]);
        };
    }, [])

    useEffect(() => {
        console.log(userListState)
    }, [userListState])

    const fetchUsersNoComment = () => {
        axios.post('')
            .then((response) => {
                //API call
                const data = response.data
                console.log('blog data', data)
                if (data['Is Success'] === 0) {
                    alert(data['Message'])
                } else {
                    setuserListState(response.data['userlist'])
                    setUserFromSearch(false)
                }
            }).catch(error => {
                console.log('error', error)
            })
    }

    const searchBtnHandler = () => {
        console.log('btn click')
        console.log('date', dateRef.current.value)
        axios.post('http://localhost/COMP440/Server/api/GetMostBlogsOnDateX.php', {
            pdate: dateRef.current.value,
        }).then((response) => {
            //API call
            const data = response.data
            console.log('userlist', data['userlist'])
            // console.log('userList', data['userlist']);
            if (data['Is Success'] === 0) {
                alert(data['Message'])
            } else {
                setuserListState(data['userlist'])
                setUserFromSearch(true)
            }
        }).catch(error => {
            console.log('error', error)
        })
    }

    return (
        <div className="list-blog-bg">
            <div className="input-group">
                <div className="form-outline search-box">
                    <input type="search" id="form1" className="form-control" placeholder="Search" ref={dateRef} />
                    <button type="button" className="search-button" onClick={searchBtnHandler}>
                        <BiSearch />
                    </button>
                </div>
            </div>
            <br />
            <div className="scrollit">
                {isUserFromSearch ? <h3>Users With Most Number of Blogs</h3> : <h3>UsersList Who Never Posted a Comment</h3>}
                <table className="table table-striped">
                    <tbody>
                        {
                            isUserFromSearch && userListState && userListState.map((user, index) => {
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

export default UsersList;