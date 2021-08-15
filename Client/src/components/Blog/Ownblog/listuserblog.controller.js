import React, { useState, useEffect } from 'react'
import axios from 'axios';
import { Redirect } from 'react-router-dom';
import ListBlog from '../../Blog/ListBlog/listblog.component'


function ListOwnBlog(props) {

    const [blogState, setBlogState] = useState([{}])

    useEffect(() => {
        // fetchUserBlogsPstCommt();
        return () => {
            setBlogState([{}]);
        };
    }, [])

    useEffect(() => {
        console.log(blogState)
    }, [blogState])

    const fetchUserBlogsPstCommt = () => {
        //change url
        axios.get('')
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

    return (
        <div className="list-blog-bg">
            <div className="scrollit">
                <table className="table table-striped">
                    {/* //setup for loop here */}
                    <tbody>
                        <h1>Hello</h1>
                        {/* {
                            blogState && blogState.map((blog, index) => {
                                return (
                                    <tr key={index}>
                                        <td onClick={() => blogClickHandler(blog.subject, blog.description, blog.blogid, blog.userid)}>
                                            <ListBlog subject={blog.subject} description={blog.description} />
                                        </td>
                                    </tr>
                                )
                            })
                        } */}
                    </tbody>
                </table>
            </div>
        </div>
    )
}

export default ListOwnBlog;