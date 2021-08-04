import React, { useState, useEffect } from 'react'
import axios from 'axios';
import { Redirect } from 'react-router-dom';
import MainHeader from './MainHeader/MainHeader'
import ListBlog from '../../Blog/ListBlog/listblog.component'
import './dashboard.css'

function Dashboard(props) {

    const [blogState, setBlogState] = useState([{}])

    useEffect(() => {
        fetchBlogs();
    }, [])

    useEffect(() => {
        console.log(blogState)
    }, [blogState])

    const fetchBlogs = async () => {
        axios.get('http://localhost/COMP440/Server/api/GetBlogs.php')
            .then((response) => {
                //API call
                const data = response.data
                if (data['Is Success'] === 0) {
                    alert(data['Message'])
                } else {
                    setBlogState(response.data['blogslist'])
                }
            }).catch(error => {
                console.log('error', error)
            })
    }



    if (sessionStorage.getItem('isUserLogin') === null) {
        return <Redirect to="/sign-in" />;
    }

    const blogClickHandler = () => {
        props.history.push('/detailblog')
    }

    return (
        <div className="list-blog-bg">
            <MainHeader />
            {/* <h3>Welcome {props.location.state.username}!!</h3> */}
            <div className="scrollit">
                <table className="table table-striped">
                    {/* //setup for loop here */}
                    <tbody>
                        {
                            blogState && blogState.map(blog => {
                                return (
                                    <tr>
                                        <td onClick={blogClickHandler}>
                                            <ListBlog subject={blog.subject} description={blog.description} />
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

export default Dashboard;