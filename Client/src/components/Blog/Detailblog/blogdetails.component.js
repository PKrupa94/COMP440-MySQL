import React, { useState, useEffect } from 'react'
import axios from 'axios'
import './blogdetails.css'
import { BiCommentAdd } from 'react-icons/bi'
import ListBlog from '../ListBlog/listblog.component'

function BlogDetails() {

    const [isShowCommentBox, setShowCommentBox] = useState(false)
    // const [reviewTypeState, setReviewTypeState] = useState('Positive')
    const [commentstate, setCommentState] = useState({
        commentDescription: '',
        sentiment: 'Positive'
    })

    const [commentList, setCommentList] = useState([{}])

    useEffect(() => {
        fetchListOfComments();
    }, [])

    useEffect(() => {
        console.log(commentList)
    }, [commentList])

    const fetchListOfComments = async () => {
        axios.post('http://localhost/COMP440/Server/Classes/getComments.php', { blogid: 8 })
            .then((response) => {
                //API call
                const data = response.data
                if (data['Is Success'] === 0) {
                    alert(data['Message'])
                } else {
                    console.log('comment', data.commentlist)
                    setCommentList(data.commentlist)
                }
            }).catch(error => {
                console.log('error', error)
            })
    }

    const btnAddCommentHandler = () => {
        setShowCommentBox(true)
        if (isShowCommentBox) {
            console.log('button click')
            console.log(commentstate)
            //add API Call here

            axios.post('', {
                sentiment: commentstate.sentiment,
                description: commentstate.commentDescription,
                blogid: '',
                posted_by: ''
            }).then(response => {
                console.log('response', response)
            }).catch(error => {
                console.log('error', error)
            })
        }
    }

    return (
        <div className="detail-blog-bg">
            <h1>blog details</h1>
            <hr />
            <p>These courses are open to anyone meeting the minimum requirements for the relevant level.
                The six Introductory courses released previously continue to be available for anyone at the Beginners level who has completed at least Level 4 of the Achievement Tasks in the Newcomers Community.</p>
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
            <button className="blog-button" onClick={btnAddCommentHandler}>Add Comment <BiCommentAdd /></button>
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
    )

}

export default BlogDetails;