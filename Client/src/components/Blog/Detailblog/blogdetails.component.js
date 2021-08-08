import React, { useState, useEffect } from 'react'
import axios from 'axios'
import './blogdetails.css'
import { BiCommentAdd } from 'react-icons/bi'
import ListBlog from '../ListBlog/listblog.component'
import Alert from '../../Alert/alert.component'

function BlogDetails(props) {

    const [isShowCommentBox, setShowCommentBox] = useState(false) //show comment button
    const [isUserCommented, setUserCommented] = useState(false) //for use can not add more than one comment

    const [commentstate, setCommentState] = useState({
        commentDescription: '',
        sentiment: 'Positive'
    }) // store comment data for api call
    const [commentList, setCommentList] = useState([{}]) // store data from API
    const [isSuccess, setSuccess] = useState(false)

    useEffect(() => {
        fetchListOfComments();
        return () => {
            setCommentList([{}]);
        };
    }, [])

    useEffect(() => {
        console.log(commentList)
    }, [commentList])

    const fetchListOfComments = () => {
        axios.post('http://localhost/COMP440/Server/Classes/getComments.php', { blogid: props.location.state.blogid })
            .then((response) => {
                //API call
                console.log('response', response)
                const data = response.data
                if (data['Is Success'] === 0) {
                    alert(data['Message'])
                } else {
                    console.log('comment', data.commentlist)
                    setCommentList(data.commentlist)
                    isUserAlredyCommented(data.commentlist)
                }
            }).catch(error => {
                console.log('error', error)
            })
    }

    const isUserAlredyCommented = (data) => {
        const userId = sessionStorage.getItem('userId')
        for (var key in data) {
            console.log('key', data[key]['authorid'])
            if (userId === data[key]['authorid']) {
                setUserCommented(true)
                return
            }
        }


    }

    const btnAddCommentHandler = () => {
        setShowCommentBox(true)
        if (isShowCommentBox) {
            axios.post('http://localhost/COMP440/Server/Classes/comments.php', {
                sentiment: commentstate.sentiment,
                description: commentstate.commentDescription,
                blogid: props.location.state.blogid,
                authorid: sessionStorage.getItem('userId')
            }).then(response => {
                console.log('response', response)
                setSuccess(true)
                setShowCommentBox(false)
                fetchListOfComments()
            }).catch(error => {
                console.log('error', error)
            })
        }
    }

    return (
        <div className="blog-alert-success">
            {isSuccess ?
                <Alert message="Your Comment is succesfully Saved!!!" />
                : null
            }
            <div className="detail-blog-bg">
                <h1>{props.location.state.subject}</h1>
                <hr />
                <p>{props.location.state.description}</p>
                <hr />
                <div className="scrollit comment-list">
                    <h4>Comments</h4>
                    <table className="table table-striped">
                        {/* //setup for loop here */}
                        <tbody>
                            {
                                commentList && commentList.map((comment, index) => {
                                    return (
                                        <tr key={index}>
                                            <td>
                                                <ListBlog subject={comment.sentiment} description={comment.description} />
                                            </td>
                                        </tr>
                                    )
                                })
                            }
                        </tbody>
                    </table>
                </div>
                {
                    props.location.state.userid === sessionStorage.getItem("userId") || isUserCommented ? null :
                        <button className="blog-button" onClick={() => btnAddCommentHandler()}>Add Comment <BiCommentAdd /></button>
                }
                {isShowCommentBox ?
                    <div className="comment-blog-bg">
                        <div className="form-check form-check-inline">
                            <input className="form-check-input"
                                type="radio"
                                name="reviewType"
                                id="inlineRadio1"
                                value="Positive"
                                onChange={e => setCommentState({ ...commentstate, sentiment: e.target.value })}
                            />
                            <label className="form-check-label">Positive</label>
                        </div>
                        <div className="form-check form-check-inline">
                            <input className="form-check-input"
                                type="radio"
                                name="reviewType"
                                id="inlineRadio2"
                                value="Negative"
                                onChange={e => setCommentState({ ...commentstate, sentiment: e.target.value })}
                            />
                            <label className="form-check-label">Negative</label>
                        </div>
                        <input type="text"
                            className="form-control"
                            onChange={e => setCommentState({ ...commentstate, commentDescription: e.target.value })}
                        />
                    </div> : null}
            </div>
        </div>
    )

}

export default BlogDetails;