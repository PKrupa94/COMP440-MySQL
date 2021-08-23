import React, { useState, useEffect, useRef } from 'react'
import axios from 'axios';
import { Redirect } from 'react-router-dom';
import MainHeader from './MainHeader/MainHeader'
import ListBlog from '../../Blog/ListBlog/listblog.component'
import { BiSearch } from 'react-icons/bi'
import './dashboard.css'

function Dashboard(props) {
    const [blogState, setBlogState] = useState([{}])
    const tagRef = useRef();

    useEffect(() => {
        fetchBlogs();
        return () => {
            setBlogState([{}]);
        };
    }, [])

    useEffect(() => {
        console.log(blogState)
    }, [blogState])

    const fetchBlogs = () => {
        axios.get('http://localhost/COMP440/Server/api/GetBlogs.php')
            .then((response) => {
                //API call
                const data = response.data
                console.log('blog data', data)
                if (data['Is Success'] === 0) {
                    alert(data['Message'])
                } else {
                    setBlogState(response.data['blogslist'])
                }
            }).catch(error => {
                console.log('error', error)
            })
    }

    const blogClickHandler = (subject, description, blogid, userid) => {
        props.history.push('/detailblog', { subject: subject, description: description, blogid: blogid, userid: userid })
    }

    const searchBtnHandler = () => {
        //Need to change url
        console.log('tag', typeof (tagRef.current.value))
        console.log('search btn click')
        axios.post('http://localhost/COMP440/Server/api/GetBlogsWithTagX.php', {
            inputTag: tagRef.current.value,
        }).then(response => {
            const data = response.data
            console.log('blog data', data)
            if (data['Is Success'] === 0) {
                alert(data['Message'])
            } else {
                console.log(response.data['blogslist'])
                setBlogState(response.data['blogslist'])
            }
        }).catch(error => {
            console.log('error', error)
        })

    }

    if (sessionStorage.getItem('isUserLogin') === null) {
        return <Redirect to="/sign-in" />;
    }

    return (
        <div className="list-blog-bg">
            <MainHeader />
            <div className="input-group">
                <div className="form-outline search-box">
                    <input type="search" id="form1" className="form-control" placeholder="Search" ref={tagRef} />
                    <button type="button" className="search-button" onClick={searchBtnHandler}>
                        <BiSearch />
                    </button>
                </div>

            </div>
            <br />
            {/* <h3>Welcome {props.location.state.username}!!</h3> */}
            <div className="scrollit">
                <table className="table table-striped">
                    {/* //setup for loop here */}
                    <tbody>
                        {
                            blogState && blogState.map((blog, index) => {
                                return (
                                    <tr key={index}>
                                        <td onClick={() => blogClickHandler(blog.subject, blog.description, blog.blogid, blog.userid)}>
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