import React, { useState } from 'react'
import axios from 'axios'
import './createnewblog.css'
import Alert from '../../Alert/alert.component'

function CreateNewBlog(props) {

    const newBlogInitialState = {
        subject: '',
        description: '',
        tags: []
    }

    const [newBlogState, setNewBlogState] = useState(newBlogInitialState)
    const [isSuccess, setSuccess] = useState(false)

    const btnCreateArticleHandler = () => {
        console.log('button click')
        console.log('subject', newBlogState.subject)
        console.log('description', newBlogState.description)
        console.log('tags', newBlogState.tags)
        setSuccess(true)
        //setup api call
        // axios.post('', {
        //     subject: newBlogState.subject,
        //     description: newBlogState.description,
        //     tag: newBlogState.tags
        // }).then(response => {
        //     console.log('response', response)

        // }).catch(error => {
        //     console.log('error', error)
        // })

    }



    return (
        <div className="blog-alert-success">
            {isSuccess ?
                <Alert message="Your blog is succesfully Saved!!!" />
                : null
            }
            <div className="article-width auth-inner">
                <h3>New Article</h3>
                <div className="form-group article-padding-bottom">
                    <label>Subject</label>
                    <input type="text"
                        className="form-control"
                        placeholder="Title"
                        onChange={e => setNewBlogState({ ...newBlogState, subject: e.target.value })}
                        required />
                </div>
                <div className="form-group article-padding-bottom">
                    <label>Description</label>
                    <textarea className="form-control"
                        rows="3"
                        onChange={e => setNewBlogState({ ...newBlogState, description: e.target.value })}
                        required>
                    </textarea>
                </div>
                <div className="form-group article-padding-bottom">
                    <label>Tags</label>
                    <input type="text"
                        className="form-control"
                        placeholder="Tags"
                        onChange={e => setNewBlogState({ ...newBlogState, tags: e.target.value.split(',') })}
                        required />
                </div>
                <button className="article-button" onClick={btnCreateArticleHandler}>Create Article</button>
            </div>
        </div>
    )



}

export default CreateNewBlog;