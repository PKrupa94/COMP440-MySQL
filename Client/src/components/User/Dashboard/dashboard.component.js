import React from 'react'
import { Redirect } from 'react-router-dom';
import MainHeader from './MainHeader/MainHeader'
import ListBlog from '../../Blog/ListBlog/listblog.component'
import './dashboard.css'



function dashboard(props) {

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
                        <tr>
                            <td onClick={blogClickHandler}>
                                <ListBlog />
                            </td>
                        </tr>
                        <tr>
                            <td onClick={blogClickHandler}>
                                <ListBlog />
                            </td>
                        </tr>
                        <tr>
                            <td onClick={blogClickHandler}>
                                <ListBlog />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ListBlog />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ListBlog />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ListBlog />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ListBlog />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    )
}

export default dashboard;