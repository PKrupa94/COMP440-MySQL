import React, { useContext, useState } from 'react';
import AuthContext from '../../../../AuthContext'
import axios from 'axios';
import Alert from '../../../Alert/alert.component';
import classes from './Navigation.module.css';

const Navigation = () => {
  const ctxt = useContext(AuthContext)

  const initDatabaseHandler = () => {
    // need to set up api call
    axios.get('http://localhost/COMP440/Server/api/initDatabase.php').then(response => {
      console.log('response', response)
      const data = response['data']
      if (data['Is Success'] === 1) {
        alert(data['Message'])
      }
    }).catch(error => {
      console.log('error', error)
    })


    console.log('button click')
  }

  return (
    <nav className={classes.nav}>
      <ul>
        {ctxt.isLoggedIn && (
          <li>
            <a href="/newblog">Create New Article</a>
          </li>
        )}
        {ctxt.isLoggedIn && (
          <li>
            <a href="/myblog">My Blogs</a>
          </li>
        )}
        {ctxt.isLoggedIn && (
          <li>
            <a href="/followers">Followers</a>
          </li>
        )}
        {ctxt.isLoggedIn && (
          <li>
            <a href="/userslist">UsersList</a>
          </li>
        )}
        {ctxt.isLoggedIn && (
          <li>
            <button onClick={initDatabaseHandler}>Initialize  Database</button>
          </li>
        )}

        {ctxt.isLoggedIn && (
          <li>
            <button onClick={ctxt.onLogout}>Logout</button>
          </li>
        )}

      </ul>
    </nav>
  )
};

export default Navigation;
